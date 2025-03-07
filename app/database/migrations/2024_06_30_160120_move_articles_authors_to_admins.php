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
        // SQLite doesn't support dropping foreign keys, so we'll skip that operation
        // and just update the data
        
        // Uncomment this when not using SQLite
        // \App\Models\Article::query()->update([
        //     'created_by' => \App\Models\Admin::getAiWriter()->id
        // ]);
        
        // For SQLite compatibility, we'll skip foreign key operations
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For SQLite compatibility, we'll skip foreign key operations
    }
};
