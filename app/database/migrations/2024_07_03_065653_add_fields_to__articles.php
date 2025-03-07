<?php

use App\Models\Article;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Only add source column since type already exists
            $table->string('source')->after('status')->default(Article::SOURCES['author'])->index();
            // Skip adding type column as it already exists
            // $table->string('type')->after('source')->default(Article::TYPES['plain'])->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['type', 'source']);
        });
    }
};
