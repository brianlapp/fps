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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->text('description');
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->json('affiliate_links')->nullable();
            $table->text('deals')->nullable();
            $table->float('price_from')->nullable();
            $table->float('price_to')->nullable();
            $table->string('price_range_string')->nullable();
            $table->tinyInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
