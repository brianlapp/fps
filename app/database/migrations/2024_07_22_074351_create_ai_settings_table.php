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
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();
            $table->string('function');
            $table->text('instructions')->nullable();
            $table->text('prompt')->nullable();
            $table->string('model');
            $table->float('temperature', 2, 1, true);
            $table->timestamps();
        });

        foreach (\App\Models\AiSettings::DEFAULTS as $item) {
            \App\Models\AiSettings::create($item);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_settings');
    }
};
