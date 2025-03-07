<?php

use App\Models\PlannerCategory;
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
        Schema::create('planner_items', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignIdFor(PlannerCategory::class);
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('color')->nullable();
            $table->string('label')->nullable();
            $table->tinyInteger('order')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('planner_category_id')->references('id')->on('planner_categories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planner_items');
    }
};
