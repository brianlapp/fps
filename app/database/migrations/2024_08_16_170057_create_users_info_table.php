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
        Schema::create('user_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('child_age')->nullable();
            $table->string('phone')->nullable();
            $table->string('postcode')->nullable();
            $table->string('state')->nullable();
            $table->date('child_dob')->nullable();
            $table->string('lang')->nullable()->default('en');
            $table->boolean('cst_consent')->default(false);
            $table->boolean('embark_consent')->default(false);

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_info');
    }
};
