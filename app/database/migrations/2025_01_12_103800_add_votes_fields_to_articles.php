<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedInteger('vote_avg')->default(0)->after('published_at');
            $table->unsignedInteger('vote_sum')->default(0)->after('vote_avg');
            $table->unsignedInteger('vote_cnt')->default(0)->after('vote_sum');
            $table->json('vote_stat')->nullable()->after('vote_cnt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['vote_avg', 'vote_sum','vote_cnt', 'vote_stat']);
        });
    }
};
