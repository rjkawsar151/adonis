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
        Schema::table('blogs', function (Blueprint $table) {
            $table->index(['status', 'createdAt']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->index(['date', 'branchId']);
        });

        Schema::table('price_list_items', function (Blueprint $table) {
            $table->index(['branch_id', 'sort_order']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->index('category');
            if (Schema::hasColumn('services', 'branch_id')) {
                $table->index('branch_id');
            }
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
            $table->dropIndex(['status', 'createdAt']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['date', 'branchId']);
        });

        Schema::table('price_list_items', function (Blueprint $table) {
            $table->dropIndex(['branch_id', 'sort_order']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex(['category']);
            if (Schema::hasColumn('services', 'branch_id')) {
                $table->dropIndex(['branch_id']);
            }
        });
    }
};
