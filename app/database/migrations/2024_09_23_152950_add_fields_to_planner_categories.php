<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('planner_categories', function (Blueprint $table) {
            $table->boolean('show_image')->default(false)->after('order');
            $table->boolean('as_article')->default(false)->after('show_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planner_categories', function (Blueprint $table) {
            $table->dropColumn(['as_article', 'show_image']);
        });
    }
};
