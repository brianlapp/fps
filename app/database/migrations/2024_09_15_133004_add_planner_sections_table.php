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
        Schema::create('planner_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PlannerCategory::class);
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('color')->nullable();
            $table->text('links')->nullable();
            $table->tinyInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('planner_category_id')->references('id')->on('planner_categories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planner_sections');
    }
};
