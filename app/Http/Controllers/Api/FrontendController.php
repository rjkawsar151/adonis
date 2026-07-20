<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller
{
    public function services()
    {
        $branchId = request()->query('branch_id', 'all');
        $cacheKey = 'adonis_services_' . $branchId;

        return response()->json(Cache::remember($cacheKey, 3600, function () use ($branchId) {
            $query = DB::table('services')->orderBy('id');

            if ($branchId !== 'all') {
                $query->where(function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId)
                      ->orWhere('branch_id', 'all')
                      ->orWhereNull('branch_id');
                });
            }

            return $query->get()->map(fn ($row) => [
                'id' => $row->id,
                'name' => $row->name,
                'description' => $row->description,
                'durationMin' => (int) $row->durationMin,
                'priceBDT' => (int) $row->priceBDT,
                'category' => $row->category,
                'icon' => $row->icon,
                'branch_id' => $row->branch_id,
                'price' => $row->price,
            ])->all();
        }));
    }

    public function barbers()
    {
        return response()->json(Cache::remember('adonis_barbers', 3600, function () {
            return DB::table('barbers')->orderBy('id')->get()->map(fn ($row) => [
                'id' => $row->id,
                'name' => $row->name,
                'experienceYears' => (int) $row->experienceYears,
                'specialty' => $row->specialty,
                'portraitUrl' => $row->portraitUrl,
                'bio' => $row->bio,
                'rating' => (float) $row->rating,
            ])->all();
        }));
    }

    public function blogs()
    {
        return response()->json(Cache::remember('adonis_blogs_v2', 3600, function () {
            $posts = \App\Models\Blog::with(['author', 'category', 'tags'])
                ->where('status', 'published')
                ->where(function($q) {
                    $q->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
                })
                ->orderByDesc('createdAt')
                ->get();

            $categories = \App\Models\BlogCategory::where('status', true)->get();
            $tags = \App\Models\BlogTag::where('status', true)->get();

            return [
                'posts' => $posts,
                'categories' => $categories,
                'tags' => $tags
            ];
        }));
    }

    public function blogDetail($slug)
    {
        // 1. Check slug redirects
        $redirect = \App\Models\BlogSlugRedirect::where('old_slug', $slug)->first();
        if ($redirect) {
            return response()->json([
                'redirectSlug' => $redirect->new_slug
            ], 301);
        }

        $post = \App\Models\Blog::with(['author', 'category', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where(function($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->first();

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        // 2. Fetch related posts (same category or sharing tags)
        $tagIds = $post->tags->pluck('id')->toArray();
        $relatedPosts = Cache::remember("adonis_blog_related_{$post->id}", 3600, function () use ($post, $tagIds) {
            return \App\Models\Blog::with(['author', 'category'])
                ->where('id', '!=', $post->id)
                ->where('status', 'published')
                ->where(function($q) use ($post, $tagIds) {
                    $q->where('category_id', $post->category_id)
                      ->orWhereHas('tags', function($t) use ($tagIds) {
                          $t->whereIn('blog_tags.id', $tagIds);
                      });
                })
                ->orderByDesc('createdAt')
                ->take(3)
                ->get();
        });

        // 3. Prev/Next navigation
        $prevPost = \App\Models\Blog::where('status', 'published')
            ->where('createdAt', '<', $post->createdAt)
            ->orderByDesc('createdAt')
            ->first(['slug', 'title']);

        $nextPost = \App\Models\Blog::where('status', 'published')
            ->where('createdAt', '>', $post->createdAt)
            ->orderBy('createdAt')
            ->first(['slug', 'title']);

        return response()->json([
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'prevPost' => $prevPost,
            'nextPost' => $nextPost
        ]);
    }

    public function categoryArchive($slug)
    {
        $category = \App\Models\BlogCategory::where('slug', $slug)->where('status', true)->firstOrFail();
        $posts = \App\Models\Blog::with(['author', 'category', 'tags'])
            ->where('category_id', $category->id)
            ->where('status', 'published')
            ->orderByDesc('createdAt')
            ->get();

        return response()->json([
            'category' => $category,
            'posts' => $posts
        ]);
    }

    public function tagArchive($slug)
    {
        $tag = \App\Models\BlogTag::where('slug', $slug)->where('status', true)->firstOrFail();
        $posts = \App\Models\Blog::with(['author', 'category', 'tags'])
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('tag_id', $tag->id);
            })
            ->where('status', 'published')
            ->orderByDesc('createdAt')
            ->get();

        return response()->json([
            'tag' => $tag,
            'posts' => $posts
        ]);
    }

    public function authorArchive($id)
    {
        $author = \App\Models\BlogAuthor::where('id', $id)->where('status', true)->firstOrFail();
        $posts = \App\Models\Blog::with(['author', 'category', 'tags'])
            ->where('author_id', $author->id)
            ->where('status', 'published')
            ->orderByDesc('createdAt')
            ->get();

        return response()->json([
            'author' => $author,
            'posts' => $posts
        ]);
    }

    public function sitemapXml()
    {
        $baseUrl = url('/');
        if (str_contains($baseUrl, 'localhost') || str_contains($baseUrl, '127.0.0.1')) {
            $baseUrl = 'https://adonis.com.bd';
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // 1. Static Pages
        $xml .= "<url><loc>{$baseUrl}/</loc><priority>1.0</priority><changefreq>daily</changefreq></url>";
        $xml .= "<url><loc>{$baseUrl}/services</loc><priority>0.8</priority><changefreq>weekly</changefreq></url>";
        $xml .= "<url><loc>{$baseUrl}/services/gulshan</loc><priority>0.8</priority><changefreq>weekly</changefreq></url>";
        $xml .= "<url><loc>{$baseUrl}/services/bashundhara</loc><priority>0.8</priority><changefreq>weekly</changefreq></url>";
        $xml .= "<url><loc>{$baseUrl}/about</loc><priority>0.8</priority><changefreq>monthly</changefreq></url>";
        $xml .= "<url><loc>{$baseUrl}/career</loc><priority>0.7</priority><changefreq>weekly</changefreq></url>";
        $xml .= "<url><loc>{$baseUrl}/blog</loc><priority>0.8</priority><changefreq>daily</changefreq></url>";
        $xml .= "<url><loc>{$baseUrl}/privacy-policy</loc><priority>0.5</priority><changefreq>monthly</changefreq></url>";

        // 2. Blog Posts
        $posts = \App\Models\Blog::where('status', 'published')->get();
        foreach ($posts as $post) {
            $xml .= "<url><loc>{$baseUrl}/blog/{$post->slug}</loc><priority>0.6</priority><changefreq>weekly</changefreq></url>";
        }

        // 3. Categories
        $categories = \App\Models\BlogCategory::where('status', true)->get();
        foreach ($categories as $cat) {
            $xml .= "<url><loc>{$baseUrl}/blog/category/{$cat->slug}</loc><priority>0.5</priority><changefreq>weekly</changefreq></url>";
        }

        // 4. Tags
        $tags = \App\Models\BlogTag::where('status', true)->get();
        foreach ($tags as $tag) {
            $xml .= "<url><loc>{$baseUrl}/blog/tag/{$tag->slug}</loc><priority>0.4</priority><changefreq>monthly</changefreq></url>";
        }

        // 5. Careers (Job Listings)
        if (\Illuminate\Support\Facades\Schema::hasTable('careers')) {
            $jobs = \App\Models\Career::active()->get();
            foreach ($jobs as $job) {
                $xml .= "<url><loc>{$baseUrl}/career/{$job->slug}</loc><priority>0.6</priority><changefreq>weekly</changefreq></url>";
            }
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function priceList($branch = 'all')
    {
        $cacheKey = 'adonis_price_list_' . $branch;

        return response()->json(Cache::remember($cacheKey, 3600, function () use ($branch) {
            $query = DB::table('price_list_items')->orderBy('sort_order')->orderBy('id');

            if ($branch !== 'all') {
                $query->where(function ($q) use ($branch) {
                    $q->where('branch_id', $branch)
                      ->orWhere('branch_id', 'all')
                      ->orWhereNull('branch_id');
                });
            }

            $items = $query->get();
            $groups = [];

            foreach ($items as $item) {
                $cat = $item->category;
                if (!isset($groups[$cat])) {
                    $groups[$cat] = ['category' => $cat, 'items' => []];
                }
                $groups[$cat]['items'][] = [
                    'name' => $item->name,
                    'price' => $item->price,
                    'description' => $item->description,
                ];
            }

            return array_values($groups);
        }));
    }

    public function about()
    {
        return response()->json(Cache::remember('adonis_about_page_data', 3600, function () {
            // Check if tables are ready. If not, return default mock data structure.
            if (!\Illuminate\Support\Facades\Schema::hasTable('about_chairman_messages')) {
                return $this->fallbackAboutData();
            }

            try {
                $teamMembers = DB::table('about_team_members')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderByDesc('id')
                    ->get()
                    ->unique(fn ($member) => mb_strtolower(trim($member->name)))
                    ->values();

                $data = [
                    'chairmanMessage' => DB::table('about_chairman_messages')->where('is_active', true)->first(),
                    'mdMessage' => \Illuminate\Support\Facades\Schema::hasTable('about_md_messages') ? DB::table('about_md_messages')->where('is_active', true)->first() : null,
                    'companyIntro' => DB::table('about_company_introductions')->where('is_active', true)->first(),
                    'missionsVisions' => DB::table('about_missions_visions')->where('is_active', true)->orderBy('sort_order')->get(),
                    'coreValues' => DB::table('about_core_values')->where('is_active', true)->orderBy('sort_order')->get(),
                    'whyChooseUs' => DB::table('about_why_choose_us')->where('is_active', true)->orderBy('sort_order')->get(),
                    'statistics' => DB::table('about_statistics')->where('is_active', true)->orderBy('sort_order')->get(),
                    'timelines' => DB::table('about_timelines')->where('is_active', true)->orderBy('sort_order')->get(),
                    'teamMembers' => $teamMembers,
                    'cta' => DB::table('about_ctas')->where('is_active', true)->first(),
                ];

                $normalizeUrl = static function ($path) {
                    if (!$path || preg_match('~^(?:https?:)?//~i', $path)) {
                        return $path;
                    }

                    return '/' . ltrim($path, '/');
                };

                foreach (['chairmanMessage', 'mdMessage'] as $messageKey) {
                    if ($data[$messageKey]) {
                        $data[$messageKey]->photo = $normalizeUrl($data[$messageKey]->photo);
                        $data[$messageKey]->signature_image = $normalizeUrl($data[$messageKey]->signature_image);
                    }
                }

                if ($data['companyIntro']) {
                    $data['companyIntro']->featured_image = $normalizeUrl($data['companyIntro']->featured_image);
                }

                foreach ($data['timelines'] as $timeline) {
                    $timeline->image = $normalizeUrl($timeline->image);
                }

                foreach ($data['teamMembers'] as $member) {
                    $member->photo = $normalizeUrl($member->photo);
                }

                if ($data['cta']) {
                    $data['cta']->background_image = $normalizeUrl($data['cta']->background_image);
                }

                return $data;
            } catch (\Throwable $e) {
                return $this->fallbackAboutData();
            }
        }))->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    private function fallbackAboutData(): array
    {
        return [
            'chairmanMessage' => [
                'name' => 'Babul Chandra Shil',
                'designation' => 'Founder & Chairman',
                'photo' => '/assets/images/babul_barbar.png',
                'title' => 'Redefining Men\'s Grooming Standards',
                'speech' => '<p>Welcome to <strong>ADONIS Men\'s Grooming Salon</strong>. Since our founding, we have committed ourselves to a singular goal: providing a sanctuary of premium transition and executive grooming for the modern gentleman.</p><p>We believe that grooming is not merely a transaction—it is an experience, a curated ritual of details.</p>',
                'signature_image' => null,
                'quotation' => 'True grooming is the art of sculpting confidence from details.',
                'is_active' => true,
            ],
            'mdMessage' => [
                'name' => 'Antor Mondol',
                'designation' => 'Managing Director & Partner',
                'photo' => '/assets/images/master_barber_portrait_1779269169728.png',
                'title' => 'Uncompromising Pursuit of Perfection',
                'speech' => '<p>At Adonis, our operational focus centers entirely on standard training and absolute precision. We realize that the modern gentleman values time, consistency, and standard hospitality.</p>',
                'signature_image' => null,
                'quotation' => 'Consistency is the blueprint of luxury and lasting trust.',
                'is_active' => true,
            ],
            'companyIntro' => [
                'title' => 'Who We Are & What We Do',
                'subtitle' => 'ADONIS is Dhaka\'s premium destination for high-end men\'s grooming and relaxation.',
                'description' => '<p>Established in the heart of Dhaka\'s premium neighborhoods (Gulshan Avenue & Bashundhara), <strong>ADONIS</strong> is more than a barbershop—it is an elite lifestyle lounge designed for gentlemen who expect the very best.</p>',
                'featured_image' => '/assets/images/adonis_styling_chairs_1779270725139.png',
                'is_active' => true,
            ],
            'missionsVisions' => [
                [
                    'type' => 'mission',
                    'title' => 'Our Core Mission',
                    'short_description' => 'Redefining the grooming experience for gentlemen through unmatched craft and hospitality.',
                    'content' => '<p>Our mission is to elevate standard men\'s grooming into a curated ritual of luxury, restoration, and self-confidence.</p>',
                    'icon_or_image' => 'Compass',
                    'is_active' => true,
                ],
                [
                    'type' => 'vision',
                    'title' => 'Our Future Vision',
                    'short_description' => 'Becoming the benchmark brand for premium men\'s grooming services across South Asia.',
                    'content' => '<p>Our vision is to expand the footprint of ADONIS Lounges globally, setting a new benchmark for luxury men\'s wellness and grooming.</p>',
                    'icon_or_image' => 'Sparkles',
                    'is_active' => true,
                ]
            ],
            'coreValues' => [
                ['title' => 'Innovation', 'icon' => 'Zap', 'description' => 'Adopting advanced styling techniques & modern hair therapies.'],
                ['title' => 'Integrity', 'icon' => 'ShieldAlert', 'description' => 'Maintaining high professional standards and transparent pricing.'],
                ['title' => 'Customer Focus', 'icon' => 'Smile', 'description' => 'Centering all lounge designs around client comfort.'],
                ['title' => 'Quality', 'icon' => 'Crown', 'description' => 'Utilizing only standard tools and premium organic grooming materials.']
            ],
            'whyChooseUs' => [
                ['title' => 'Experienced Team', 'icon' => 'UserCheck', 'description' => 'Stylists with 10+ years of training and face-bone contour profiling expertise.'],
                ['title' => 'Customized Solutions', 'icon' => 'Layers', 'description' => 'Haircuts, beard shapes, and scalp therapies customized strictly to your unique style.']
            ],
            'statistics' => [
                ['counter_number' => '12', 'suffix' => '+', 'label' => 'Years of Excellence', 'icon' => 'Crown'],
                ['counter_number' => '50000', 'suffix' => '+', 'label' => 'Grooming Sessions', 'icon' => 'Scissors'],
                ['counter_number' => '25000', 'suffix' => '+', 'label' => 'Happy Gentlemen', 'icon' => 'Smile']
            ],
            'timelines' => [
                ['year_or_date' => '2014', 'title' => 'The Genesis', 'description' => 'Opened our very first grooming station in Dhaka with three master styling chairs.', 'image' => '/assets/images/executive.png'],
                ['year_or_date' => '2018', 'title' => 'Gulshan Launch', 'description' => 'Unveiled the massive Gulshan Avenue Premium Lounge featuring dynamic VIP suites.', 'image' => '/assets/images/reception.png']
            ],
            'teamMembers' => [
                [
                    'name' => 'Babul Chandra Shil',
                    'designation' => 'Chairman & Master Barber',
                    'photo' => '/assets/images/babul_barbar.png',
                    'biography' => 'Founder with over a decade of scissor design experience. Specialized in executive silhouettes.',
                    'facebook_url' => 'https://facebook.com',
                    'linkedin_url' => 'https://linkedin.com',
                    'email' => 'chairman@adonis.com.bd',
                ]
            ],
            'cta' => [
                'title' => 'Ready for a Sharper Identity?',
                'description' => 'Reserve your custom precision grooming session at our luxury Gulshan or Bashundhara lounge terminals.',
                'primary_button_text' => 'Book Appointment',
                'primary_button_url' => '#booking-section',
                'secondary_button_text' => 'Explore Services',
                'secondary_button_url' => '/services',
                'background_image' => '/assets/images/executive.png',
                'is_active' => true,
            ]
        ];
    }
}
