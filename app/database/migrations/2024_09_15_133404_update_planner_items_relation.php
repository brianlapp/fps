<?php

use App\Models\PlannerItem;
use App\Models\PlannerSection;
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
        // SQLite doesn't support dropping foreign keys, so we'll use a different approach
        PlannerItem::query()->forceDelete();
        
        // For SQLite compatibility, we'll recreate the table instead of modifying it
        Schema::dropIfExists('planner_items_temp');
        Schema::create('planner_items_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PlannerSection::class);
            // Copy all other columns from the original table except planner_category_id
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        // We'll skip copying data since we're already deleting all records above
        // Now drop the original table and rename the temp table
        Schema::dropIfExists('planner_items');
        Schema::rename('planner_items_temp', 'planner_items');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // SQLite doesn't support dropping foreign keys, so we'll use a different approach
        PlannerItem::query()->forceDelete();
        
        // For SQLite compatibility, we'll recreate the table instead of modifying it
        Schema::dropIfExists('planner_items_temp');
        Schema::create('planner_items_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\PlannerCategory::class);
            // Copy all other columns from the original table except planner_section_id
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        // We'll skip copying data since we're already deleting all records above
        // Now drop the original table and rename the temp table
        Schema::dropIfExists('planner_items');
        Schema::rename('planner_items_temp', 'planner_items');
    }
};
