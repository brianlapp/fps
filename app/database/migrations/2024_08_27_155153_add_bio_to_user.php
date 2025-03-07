<?php

use App\Models\User;
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
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->after('uuid')->nullable();
            $table->text('bio')->after('avatar')->nullable();
        });

        $users = User::query()->whereNull('slug')->get();
        foreach ($users as $user) {
            /**
             * @var $user User
             */
            $user->touch('updated_at');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'slug']);
        });
    }
};
