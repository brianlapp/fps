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
        Schema::create('article_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('client_uuid');
            $table->string('search_query');
            $table->unsignedTinyInteger('qty');
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_requests');
    }
};
