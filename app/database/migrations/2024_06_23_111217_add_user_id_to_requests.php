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
        Schema::table('article_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('client_uuid')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article_requests', function (Blueprint $table) {
            $table->dropForeign('article_requests_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }
};
