<?php

use App\Models\ArticleRequest;
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
        Schema::table('article_requests', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('user_id');
        });

        ArticleRequest::query()->update(['is_verified' => true]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article_requests', function (Blueprint $table) {
            $table->dropColumn(['is_verified']);
        });
    }
};
