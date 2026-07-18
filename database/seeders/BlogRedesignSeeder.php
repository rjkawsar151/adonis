<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogRedesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Seed Authors
        $authorId = DB::table('blog_authors')->updateOrInsert(['id' => 1], [
            'name' => 'Babul Chandra Shil',
            'profile_photo' => '/assets/images/babul_barbar.png',
            'designation' => 'Founder & Master Barber',
            'biography' => 'Babul Chandra Shil is the Founder of Adonis Men\'s Grooming. With over 12 years of professional styling and straight-razor craftsmanship, he guides our groomers and writes expert styling columns.',
            'email' => 'chairman@adonis.com.bd',
            'website' => 'https://www.adonis.com.bd',
            'facebook_url' => 'https://facebook.com',
            'linkedin_url' => 'https://linkedin.com',
            'twitter_url' => 'https://twitter.com',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Seed Categories
        DB::table('blog_categories')->updateOrInsert(['id' => 1], [
            'name' => 'Grooming Tips',
            'slug' => 'grooming-tips',
            'description' => 'Professional haircut guides, shaving styling, and styling maintenance recommendations.',
            'featured_image' => '/assets/images/adonis_styling_chairs_1779270725139.png',
            'seo_title' => 'Men\'s Grooming Tips & Styling Guides',
            'meta_description' => 'Browse our collection of expert grooming tips, haircuts suggestions, and straight-razor shaving tutorials.',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('blog_categories')->updateOrInsert(['id' => 2], [
            'name' => 'Skin & Wellness',
            'slug' => 'skin-wellness',
            'description' => 'Men\'s facials, anti-pollution scalp therapies, and detox massage treatments.',
            'featured_image' => '/assets/images/massage.png',
            'seo_title' => 'Men\'s Skin Care & Wellness Lounge Advice',
            'meta_description' => 'Explore professional tips for anti-pollution skincare, scalp protection, and relaxation treatments.',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Seed Tags
        DB::table('blog_tags')->updateOrInsert(['id' => 1], [
            'name' => 'Haircut',
            'slug' => 'haircut',
            'description' => 'Precision styling, texture blending, and high fades.',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('blog_tags')->updateOrInsert(['id' => 2], [
            'name' => 'Beard Styling',
            'slug' => 'beard-styling',
            'description' => 'Symmetrical beard contours and razor mapping.',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Update the existing default blog post with author, category, reading time, and status
        DB::table('blogs')->where('id', 'premium-mens-grooming-dhaka')->update([
            'author_id' => 1,
            'category_id' => 1,
            'is_featured' => true,
            'is_pinned' => true,
            'reading_time' => 5,
            'published_at' => '2026-06-04 00:00:00',
            'focus_keyword' => 'mens grooming dhaka',
            'secondary_keywords' => 'adonis, barbershop, gulshan, salon',
            'robots_index' => true,
            'robots_follow' => true,
            'og_title' => 'Premium Men\'s Grooming in Dhaka | Adonis Men\'s Grooming',
            'og_description' => 'Looking for the best men\'s grooming salon in Dhaka? Learn how Adonis combines precision haircuts, beard styling, facials, and premium salon service.',
            'og_image' => '/assets/images/adonis_styling_chairs_1779270725139.png',
            'schema_type' => 'BlogPosting',
            'breadcrumb_title' => 'Grooming Guide',
        ]);

        // 5. Add pivots
        DB::table('blog_tag_pivot')->updateOrInsert([
            'blog_id' => 'premium-mens-grooming-dhaka',
            'tag_id' => 1
        ]);
        DB::table('blog_tag_pivot')->updateOrInsert([
            'blog_id' => 'premium-mens-grooming-dhaka',
            'tag_id' => 2
        ]);
    }
}
