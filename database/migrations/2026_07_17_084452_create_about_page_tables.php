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
        // 1. Chairman's Message (Singleton)
        Schema::create('about_chairman_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Chairman Name');
            $table->string('designation')->default('Chairman');
            $table->string('photo')->nullable();
            $table->string('title')->default('Message from Chairman');
            $table->longText('speech')->nullable();
            $table->string('signature_image')->nullable();
            $table->text('quotation')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Company Introduction (Singleton)
        Schema::create('about_company_introductions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Who We Are');
            $table->string('subtitle')->nullable();
            $table->longText('description')->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Mission & Vision (CRUD / List)
        Schema::create('about_missions_visions', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30)->default('mission'); // mission, vision
            $table->string('title');
            $table->text('short_description')->nullable();
            $table->longText('content')->nullable();
            $table->string('icon_or_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Core Values (CRUD / List)
        Schema::create('about_core_values', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('icon')->nullable(); // Lucide icon name or path
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. Why Choose Us (CRUD / List)
        Schema::create('about_why_choose_us', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('icon')->nullable(); // Lucide icon name
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // 6. Company Statistics (CRUD / List)
        Schema::create('about_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('counter_number'); // e.g. "12", "99"
            $table->string('suffix')->nullable(); // e.g. "+", "%"
            $table->string('label');
            $table->string('icon')->nullable(); // Lucide icon name
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // 7. Company Timeline (CRUD / List)
        Schema::create('about_timelines', function (Blueprint $table) {
            $table->id();
            $table->string('year_or_date'); // e.g. "2020"
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // 8. Management Team (CRUD / List)
        Schema::create('about_team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation');
            $table->string('photo')->nullable();
            $table->text('biography')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // 9. About Page CTA (Singleton)
        Schema::create('about_ctas', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Ready for a Sharper Identity?');
            $table->text('description')->nullable();
            $table->string('primary_button_text')->default('Book Session');
            $table->string('primary_button_url')->default('#booking-section');
            $table->string('secondary_button_text')->default('Explore Services');
            $table->string('secondary_button_url')->default('/services');
            $table->string('background_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('about_ctas');
        Schema::dropIfExists('about_team_members');
        Schema::dropIfExists('about_timelines');
        Schema::dropIfExists('about_statistics');
        Schema::dropIfExists('about_why_choose_us');
        Schema::dropIfExists('about_core_values');
        Schema::dropIfExists('about_missions_visions');
        Schema::dropIfExists('about_company_introductions');
        Schema::dropIfExists('about_chairman_messages');
    }
};
