<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Create blog_authors table
        Schema::create('blog_authors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('profile_photo')->nullable();
            $table->string('designation')->nullable();
            $table->text('biography')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 2. Create blog_categories table
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. Create blog_tags table
        Schema::create('blog_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 4. Create blog_tag_pivot table
        Schema::create('blog_tag_pivot', function (Blueprint $table) {
            $table->id();
            $table->string('blog_id'); // Match type of existing blogs.id (VARCHAR)
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            $table->foreign('tag_id')->references('id')->on('blog_tags')->onDelete('cascade');
        });

        // 5. Create blog_slug_redirects table
        Schema::create('blog_slug_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('old_slug')->unique();
            $table->string('new_slug');
            $table->timestamps();
        });

        // 6. Modify existing blogs table to add columns
        Schema::table('blogs', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->after('id');
            $table->unsignedBigInteger('category_id')->nullable()->after('author_id');
            $table->boolean('is_featured')->default(false)->after('status');
            $table->boolean('is_pinned')->default(false)->after('is_featured');
            $table->integer('reading_time')->default(5)->after('is_pinned');
            $table->timestamp('published_at')->nullable()->after('reading_time');
            $table->softDeletes()->after('updatedAt');

            // On-Page SEO meta fields
            $table->string('focus_keyword')->nullable()->after('seoDescription');
            $table->text('secondary_keywords')->nullable()->after('focus_keyword');
            $table->string('canonical_url')->nullable()->after('secondary_keywords');
            $table->boolean('robots_index')->default(true)->after('canonical_url');
            $table->boolean('robots_follow')->default(true)->after('robots_index');
            $table->string('og_title')->nullable()->after('robots_follow');
            $table->text('og_description')->nullable()->after('og_title');
            $table->string('og_image')->nullable()->after('og_description');
            $table->string('twitter_title')->nullable()->after('og_image');
            $table->text('twitter_description')->nullable()->after('twitter_title');
            $table->string('twitter_image')->nullable()->after('twitter_description');
            $table->string('schema_type')->default('BlogPosting')->after('twitter_image');
            $table->string('breadcrumb_title')->nullable()->after('schema_type');

            // Foreign keys
            $table->foreign('author_id')->references('id')->on('blog_authors')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('blog_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'author_id', 'category_id', 'is_featured', 'is_pinned', 'reading_time', 'published_at',
                'focus_keyword', 'secondary_keywords', 'canonical_url', 'robots_index', 'robots_follow',
                'og_title', 'og_description', 'og_image', 'twitter_title', 'twitter_description',
                'twitter_image', 'schema_type', 'breadcrumb_title'
            ]);
            $table->dropSoftDeletes();
        });

        Schema::dropIfExists('blog_slug_redirects');
        Schema::dropIfExists('blog_tag_pivot');
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('blog_authors');
    }
};
