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
        Schema::create('about_md_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('MD Name');
            $table->string('designation')->default('Managing Director');
            $table->string('photo')->nullable();
            $table->string('title')->default('Message from Managing Director');
            $table->longText('speech')->nullable();
            $table->string('signature_image')->nullable();
            $table->text('quotation')->nullable();
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
        Schema::dropIfExists('about_md_messages');
    }
};
