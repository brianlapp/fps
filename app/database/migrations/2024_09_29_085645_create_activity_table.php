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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('child_age');
            $table->string('available_time');
            $table->string('location')->nullable();
            $table->json('activity_type_preferences')->nullable();
            $table->text('available_resources')->nullable();
            $table->text('special_requests')->nullable();
            $table->unsignedTinyInteger('status')->default(\App\Models\Activity::STATUSES['generating']);
            $table->text('result')->nullable();
            $table->text('title')->nullable();
            $table->text('brief_description')->nullable();
            $table->text('age_range')->nullable();
            $table->text('time_required')->nullable();
            $table->text('materials_needed')->nullable();
            $table->text('error')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->boolean('is_duplicate')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
