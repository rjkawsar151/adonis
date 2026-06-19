<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add missing columns to services
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'category_id')) {
                $table->string('category_id', 120)->nullable()->after('id');
                $table->string('title')->nullable()->after('category_id');
                $table->string('slug')->nullable()->after('title');
                $table->text('short_description')->nullable()->after('slug');
                $table->text('main_description')->nullable()->after('short_description');
                $table->string('hero_title')->nullable()->after('main_description');
                $table->string('hero_subtitle')->nullable()->after('hero_title');
                $table->string('hero_image')->nullable()->after('hero_subtitle');
                $table->string('main_image')->nullable()->after('hero_image');
                $table->string('cause_title')->nullable()->after('main_image');
                $table->text('cause_description')->nullable()->after('cause_title');
                $table->string('cause_image')->nullable()->after('cause_description');
                $table->string('treatment_title')->nullable()->after('cause_image');
                $table->text('treatment_description')->nullable()->after('treatment_title');
                $table->string('treatment_image')->nullable()->after('treatment_description');
                $table->boolean('is_featured')->default(false)->after('treatment_image');
                $table->boolean('show_in_sidebar')->default(false)->after('is_featured');
                $table->string('status', 30)->default('active')->after('show_in_sidebar');
                $table->integer('sort_order')->default(0)->after('status');
                $table->string('seo_title')->nullable()->after('sort_order');
                $table->text('seo_description')->nullable()->after('seo_title');
            }
        });

        // 2. service_bullets
        if (!Schema::hasTable('service_bullets')) {
            Schema::create('service_bullets', function (Blueprint $table) {
                $table->id();
                $table->string('service_id', 120);
                $table->string('section_type', 60)->default('cause');
                $table->text('bullet_text');
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 3. service_steps
        if (!Schema::hasTable('service_steps')) {
            Schema::create('service_steps', function (Blueprint $table) {
                $table->id();
                $table->string('service_id', 120);
                $table->integer('step_number')->nullable();
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->integer('sort_order')->default(0);
                $table->string('status', 30)->default('active');
                $table->timestamps();
            });
        }

        // 4. website_settings
        if (!Schema::hasTable('website_settings')) {
            Schema::create('website_settings', function (Blueprint $table) {
                $table->id();
                $table->string('site_name')->nullable();
                $table->string('logo')->nullable();
                $table->string('favicon')->nullable();
                $table->string('hero_image')->nullable();
                $table->string('primary_color')->nullable();
                $table->string('secondary_color')->nullable();
                $table->string('appointment_button_text')->nullable();
                $table->string('appointment_button_url')->nullable();
                $table->string('whatsapp_number')->nullable();
                $table->string('smtp_mail_to')->nullable();
                $table->string('smtp_host')->nullable();
                $table->string('smtp_port')->nullable();
                $table->string('smtp_username')->nullable();
                $table->string('smtp_password')->nullable();
                $table->string('smtp_encryption')->nullable();
                $table->text('notification_emails')->nullable();
                $table->timestamps();
            });
        }

        // 5. footer_settings
        if (!Schema::hasTable('footer_settings')) {
            Schema::create('footer_settings', function (Blueprint $table) {
                $table->id();
                $table->string('logo')->nullable();
                $table->text('description')->nullable();
                $table->string('address')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('facebook_url')->nullable();
                $table->string('instagram_url')->nullable();
                $table->string('linkedin_url')->nullable();
                $table->string('pinterest_url')->nullable();
                $table->string('copyright_text')->nullable();
                $table->timestamps();
            });
        }

        // 6. carousel_images
        if (!Schema::hasTable('carousel_images')) {
            Schema::create('carousel_images', function (Blueprint $table) {
                $table->id();
                $table->string('image_path');
                $table->string('alt_text')->nullable();
                $table->string('link_url')->nullable();
                $table->integer('sort_order')->default(0);
                $table->string('status', 30)->default('active');
                $table->timestamps();
            });
        }

        // Seed default settings
        if (\Illuminate\Support\Facades\DB::table('website_settings')->count() === 0) {
            \Illuminate\Support\Facades\DB::table('website_settings')->insert([
                'id' => 1,
                'site_name' => 'Adonis Men\'s Grooming',
                'appointment_button_text' => 'Book Appointment',
                'appointment_button_url' => '#booking',
                'whatsapp_number' => '+8801700000000',
                'smtp_mail_to' => 'info@adonis.com.bd',
                'notification_emails' => 'info@adonis.com.bd',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (\Illuminate\Support\Facades\DB::table('footer_settings')->count() === 0) {
            \Illuminate\Support\Facades\DB::table('footer_settings')->insert([
                'id' => 1,
                'description' => 'Dhaka\'s premier men\'s grooming destination offering expert haircuts, beard styling, facials, spa, and massage services.',
                'address' => 'Gulshan Premium Lounge, Dhaka',
                'phone' => '+8801700000000',
                'email' => 'info@adonis.com.bd',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update existing services - copy name→title, set defaults
        \Illuminate\Support\Facades\DB::statement('UPDATE services SET title = name, slug = `id`, `status` = \'active\', sort_order = 0 WHERE title IS NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('carousel_images');
        Schema::dropIfExists('footer_settings');
        Schema::dropIfExists('website_settings');
        Schema::dropIfExists('service_steps');
        Schema::dropIfExists('service_bullets');

        Schema::table('services', function (Blueprint $table) {
            $cols = ['category_id','title','slug','short_description','main_description','hero_title','hero_subtitle','hero_image','main_image','cause_title','cause_description','cause_image','treatment_title','treatment_description','treatment_image','is_featured','show_in_sidebar','status','sort_order','seo_title','seo_description'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('services', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
