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
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('remember_token');
            $table->string('github_id')->nullable()->after('google_id');
            $table->string('linkedin_id')->nullable()->after('github_id');
            $table->string('facebook_id')->nullable()->after('linkedin_id');
            $table->string('avatar')->nullable()->after('facebook_id');
            $table->string('name')->nullable(true)->change();
            $table->string('password')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['facebook_id', 'github_id', 'linkedin_id', 'google_id', 'avatar']);
            $table->string('name')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
        });
    }
};
