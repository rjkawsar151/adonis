<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('badge')->nullable();           // e.g. "LIMITED TIME", "HOT DEAL"
            $table->string('icon')->nullable();             // Lucide icon name
            $table->decimal('original_price', 10, 2)->nullable();
            $table->decimal('discounted_price', 10, 2)->nullable();
            $table->unsignedTinyInteger('discount_percent')->nullable();
            $table->string('image')->nullable();            // optional banner image
            $table->string('valid_until')->nullable();      // display string e.g. "Aug 31, 2026"
            $table->string('branch')->default('all');       // 'all', 'gulshan', 'bashundhara'
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
