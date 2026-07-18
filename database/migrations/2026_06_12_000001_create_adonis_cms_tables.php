<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->string('id', 120)->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('durationMin')->default(45);
            $table->integer('priceBDT')->default(0);
            $table->string('category', 40)->default('hair');
            $table->string('icon', 80)->default('Scissors');
        });

        Schema::create('barbers', function (Blueprint $table) {
            $table->string('id', 120)->primary();
            $table->string('name');
            $table->integer('experienceYears')->default(0);
            $table->string('specialty')->nullable();
            $table->text('portraitUrl')->nullable();
            $table->text('bio')->nullable();
            $table->decimal('rating', 3, 1)->default(5.0);
        });

        Schema::create('cms_meta', function (Blueprint $table) {
            $table->string('meta_key', 80)->primary();
            $table->longText('meta_value');
        });

        Schema::create('blogs', function (Blueprint $table) {
            $table->string('id', 120)->primary();
            $table->string('slug', 160)->unique();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->text('coverImage')->nullable();
            $table->longText('contentHtml')->nullable();
            $table->string('seoTitle')->nullable();
            $table->text('seoDescription')->nullable();
            $table->string('status', 30)->default('draft');
            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->string('id', 120)->primary();
            $table->string('clientName');
            $table->string('clientPhone');
            $table->string('clientEmail')->nullable();
            $table->string('branchId', 60);
            $table->string('barberId', 120)->nullable();
            $table->string('serviceId', 255)->nullable();
            $table->date('date');
            $table->string('time', 30);
            $table->text('notes')->nullable();
            $table->string('bookingCode', 60)->unique();
            $table->dateTime('createdAt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('cms_meta');
        Schema::dropIfExists('barbers');
        Schema::dropIfExists('services');
    }
};
