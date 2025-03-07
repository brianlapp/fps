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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('link');
            $table->text('description');
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedBigInteger('offer_category_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('offer_category_id')->references('id')->on('offer_categories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
