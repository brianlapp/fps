<?php

use App\Models\ProductReview;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('product_id');
            $table->foreignId('user_id');
            $table->string('title');
            $table->text('content');
            $table->tinyInteger('rate')->index();
            $table->tinyInteger('status')->default(ProductReview::STATUSES['pending'])->index();
            $table->boolean('is_verified_by_ai')->default(false);
            $table->text('ai_error')->nullable();
            $table->boolean('is_flagged')->default(false);
            $table->foreignId('flagged_by')->nullable();
            $table->integer('vote_rating')->default(0)->index();

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('flagged_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
