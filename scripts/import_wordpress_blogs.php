<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Models\Blog;
use App\Models\BlogAuthor;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\BlogSlugRedirect;

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$xmlPath = 'D:\\Backups\\Adonis\\adonismensgroomingsalon.WordPress.2025-08-09.xml';

if (!file_exists($xmlPath)) {
    die("Error: Backup XML file not found at $xmlPath\n");
}

echo "Starting WordPress Blog Import...\n";

$xml = simplexml_load_file($xmlPath);
$namespaces = $xml->getNamespaces(true);
$channel = $xml->channel;

// 1. Setup Upload Directory
$uploadDir = public_path('uploads/blogs');
if (!File::isDirectory($uploadDir)) {
    File::makeDirectory($uploadDir, 0755, true);
}

// 2. Import Authors
echo "\n--- Importing Authors ---\n";
$authorMap = []; // wp_login or creator -> BlogAuthor id

foreach ($channel->children($namespaces['wp'])->author as $authorXml) {
    $login = trim((string)$authorXml->author_login);
    $displayName = trim((string)$authorXml->author_display_name);
    $email = trim((string)$authorXml->author_email);
    $firstName = trim((string)$authorXml->author_first_name);
    $lastName = trim((string)$authorXml->author_last_name);

    if (empty($displayName)) {
        $displayName = $login;
    }

    $designation = 'Contributing Stylist';
    if ($login === 'adonisbd') $designation = 'Adonis Editorial Team';
    if ($login === 'Sonia') $designation = 'Senior Grooming Editor';
    if ($login === 'sharif') $designation = 'Executive Content Specialist';
    if ($login === 'Editor') $designation = 'Editorial Team Lead';

    $author = BlogAuthor::where('email', $email)
        ->orWhere('name', $displayName)
        ->first();

    if (!$author) {
        $author = BlogAuthor::create([
            'name' => $displayName,
            'email' => $email ?: "{$login}@adonis.com.bd",
            'designation' => $designation,
            'biography' => "Grooming & lifestyle writer at Adonis Men's Grooming Salon, Dhaka.",
            'status' => true
        ]);
        echo "Created Author: {$displayName} (ID: {$author->id})\n";
    } else {
        echo "Found Existing Author: {$displayName} (ID: {$author->id})\n";
    }

    $authorMap[$login] = $author->id;
    $authorMap[$displayName] = $author->id;
}

// Fallback default author
$defaultAuthor = BlogAuthor::first();
$defaultAuthorId = $defaultAuthor ? $defaultAuthor->id : 1;

// 3. Extract Attachments (Featured Images)
echo "\n--- Indexing Media Attachments ---\n";
$attachmentsMap = []; // attachment_post_id -> attachment_url

foreach ($channel->item as $item) {
    $wp = $item->children($namespaces['wp']);
    if ((string)$wp->post_type === 'attachment') {
        $postId = (string)$wp->post_id;
        $url = (string)$wp->attachment_url;
        $attachmentsMap[$postId] = $url;
    }
}
echo "Indexed " . count($attachmentsMap) . " media attachments.\n";

// Helper function to download remote images locally
function saveRemoteImageLocally($url, $uploadDir) {
    if (empty($url)) return null;
    
    $filename = basename(parse_url($url, PHP_URL_PATH));
    if (empty($filename)) return $url;
    
    $localFilePath = $uploadDir . '/' . $filename;
    $publicPath = '/uploads/blogs/' . $filename;

    if (File::exists($localFilePath)) {
        return $publicPath;
    }

    try {
        $response = Http::timeout(5)->get($url);
        if ($response->successful()) {
            File::put($localFilePath, $response->body());
            echo "  Saved image: {$filename}\n";
            return $publicPath;
        }
    } catch (\Exception $e) {
        echo "  Notice: Could not download image {$url} ({$e->getMessage()}), using original URL.\n";
    }

    return $url;
}

// 4. Process Published Posts
echo "\n--- Processing Published Blog Posts ---\n";
$importedCount = 0;
$postIndex = 0;

foreach ($channel->item as $item) {
    $wp = $item->children($namespaces['wp']);
    $dc = $item->children($namespaces['dc']);
    $contentNs = $item->children($namespaces['content']);
    $excerptNs = $item->children($namespaces['excerpt']);

    $postType = (string)$wp->post_type;
    $status = (string)$wp->status;

    if ($postType !== 'post' || $status !== 'publish') {
        continue;
    }

    $postIndex++;
    $wpPostId = (string)$wp->post_id;
    $title = trim((string)$item->title);
    $rawSlug = (string)$wp->post_name;
    $creator = (string)$dc->creator;
    $postDate = (string)$wp->post_date;
    $rawContent = (string)$contentNs->encoded;
    $rawExcerpt = (string)$excerptNs->encoded;

    // Clean / Decode slug
    $decodedSlug = urldecode($rawSlug);
    $cleanSlug = Str::slug($decodedSlug);

    // If title is Bengali and Str::slug is empty or short, use decoded or custom slug
    if (empty($cleanSlug) || strlen($cleanSlug) < 3) {
        $cleanSlug = 'blog-' . $wpPostId . '-' . Str::slug($title);
        if (empty($cleanSlug) || $cleanSlug === 'blog-' . $wpPostId . '-') {
            $cleanSlug = 'post-' . $wpPostId;
        }
    }

    echo "\nPost #{$postIndex} [ID {$wpPostId}]: {$title}\n";
    echo "  Clean Slug: {$cleanSlug}\n";

    // Category processing
    $catId = null;
    $tagIds = [];

    foreach ($item->category as $c) {
        $domain = (string)$c->attributes()->domain;
        $name = trim((string)$c);
        $slug = (string)$c->attributes()->nicename;

        if (empty($name)) continue;

        if ($domain === 'category') {
            if ($name !== 'Uncategorized') {
                $category = BlogCategory::firstOrCreate(
                    ['slug' => Str::slug($slug ?: $name)],
                    ['name' => $name, 'description' => "Articles about {$name}", 'status' => true]
                );
                $catId = $category->id;
            }
        } else if ($domain === 'post_tag') {
            $tag = BlogTag::firstOrCreate(
                ['slug' => Str::slug($slug ?: $name)],
                ['name' => $name, 'description' => "Tag: {$name}", 'status' => true]
            );
            $tagIds[] = $tag->id;
        }
    }

    // Default category if unassigned
    if (!$catId) {
        $defaultCat = BlogCategory::firstOrCreate(
            ['slug' => 'mens-grooming'],
            ['name' => "Men's Grooming", 'description' => "Men's grooming and styling advice.", 'status' => true]
        );
        $catId = $defaultCat->id;
    }

    // Author matching
    $authorId = isset($authorMap[$creator]) ? $authorMap[$creator] : $defaultAuthorId;

    // Thumbnail / Cover Image
    $thumbnailId = null;
    foreach ($wp->postmeta as $meta) {
        if ((string)$meta->meta_key === '_thumbnail_id') {
            $thumbnailId = (string)$meta->meta_value;
        }
    }
    $remoteCoverUrl = isset($attachmentsMap[$thumbnailId]) ? $attachmentsMap[$thumbnailId] : null;
    $coverImagePath = saveRemoteImageLocally($remoteCoverUrl, $uploadDir);
    if (!$coverImagePath) {
        $coverImagePath = '/assets/images/adonis_executive_lounge_1779270704894.png';
    }

    // Excerpt processing
    $plainText = strip_tags($rawContent);
    $excerpt = !empty($rawExcerpt) ? trim($rawExcerpt) : Str::limit(trim(preg_replace('/\s+/', ' ', $plainText)), 180);

    // Reading time calculation
    $wordCount = str_word_count($plainText);
    $readingTime = max(2, (int)ceil($wordCount / 200));

    // Featured status
    $isFeatured = ($postIndex <= 3);

    // Check if blog already exists by slug or title
    $blog = Blog::where('slug', $cleanSlug)
        ->orWhere('id', 'wp-' . $wpPostId)
        ->first();

    $blogData = [
        'id' => 'wp-' . $wpPostId,
        'title' => $title,
        'slug' => $cleanSlug,
        'excerpt' => $excerpt,
        'coverImage' => $coverImagePath,
        'contentHtml' => $rawContent,
        'seoTitle' => $title . " | Adonis Men's Grooming Salon",
        'seoDescription' => $excerpt,
        'status' => 'published',
        'author_id' => $authorId,
        'category_id' => $catId,
        'is_featured' => $isFeatured,
        'is_pinned' => false,
        'reading_time' => $readingTime,
        'published_at' => $postDate,
        'createdAt' => $postDate,
        'updatedAt' => now(),
        'robots_index' => true,
        'robots_follow' => true,
        'schema_type' => 'BlogPosting',
        'breadcrumb_title' => Str::limit($title, 30)
    ];

    if ($blog) {
        $blog->update($blogData);
        echo "  Updated existing blog post ID: {$blog->id}\n";
    } else {
        $blog = Blog::create($blogData);
        echo "  Created new blog post ID: {$blog->id}\n";
    }

    // Sync Tags
    if (!empty($tagIds)) {
        $blog->tags()->sync(array_unique($tagIds));
    }

    // Record slug redirects for old/encoded WP slugs
    if ($rawSlug && $rawSlug !== $cleanSlug) {
        BlogSlugRedirect::firstOrCreate(
            ['old_slug' => $rawSlug],
            ['new_slug' => $cleanSlug]
        );
        BlogSlugRedirect::firstOrCreate(
            ['old_slug' => $decodedSlug],
            ['new_slug' => $cleanSlug]
        );
        echo "  Added slug redirect: {$rawSlug} -> {$cleanSlug}\n";
    }

    $importedCount++;
}

echo "\n=========================================\n";
echo "Successfully imported/updated {$importedCount} blog posts!\n";
echo "=========================================\n";
