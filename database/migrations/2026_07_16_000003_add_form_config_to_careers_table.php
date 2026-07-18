<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->boolean('show_address')->default(true)->after('gender');
            $table->boolean('show_linkedin')->default(true)->after('show_address');
            $table->boolean('show_portfolio')->default(true)->after('show_linkedin');
            $table->boolean('show_current_company')->default(true)->after('show_portfolio');
            $table->boolean('show_current_designation')->default(true)->after('show_current_company');
            $table->boolean('show_expected_salary')->default(true)->after('show_current_designation');
            $table->boolean('show_joining_date')->default(true)->after('show_expected_salary');
            $table->boolean('show_cover_letter')->default(true)->after('show_joining_date');
        });
    }

    public function down()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->dropColumn([
                'show_address',
                'show_linkedin',
                'show_portfolio',
                'show_current_company',
                'show_current_designation',
                'show_expected_salary',
                'show_joining_date',
                'show_cover_letter'
            ]);
        });
    }
};
