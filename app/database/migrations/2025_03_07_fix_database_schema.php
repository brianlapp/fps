<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the is_featured column exists and drop it if it does
        if (Schema::hasColumn('articles', 'is_featured')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->dropColumn('is_featured');
            });
        }
        
        // Re-add the is_featured column
        Schema::table('articles', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false);
        });
        
        // Ensure site_config table has the required entries
        if (!DB::table('site_config')->where('key', 'injections')->exists()) {
            DB::table('site_config')->insert([
                'key' => 'injections',
                'value' => json_encode([
                    'header' => '',
                    'footer' => ''
                ])
            ]);
        }
        
        if (!DB::table('site_config')->where('key', 'social_settings')->exists()) {
            DB::table('site_config')->insert([
                'key' => 'social_settings',
                'value' => json_encode([
                    'seo' => [
                        'title' => 'Free Parent Search',
                        'headline' => 'Find resources and support for parents',
                        'image' => '',
                        'link' => ''
                    ],
                    'networks' => [
                        [
                            'name' => 'Facebook',
                            'url' => '#',
                            'icon' => 'facebook'
                        ],
                        [
                            'name' => 'Twitter',
                            'url' => '#',
                            'icon' => 'twitter'
                        ]
                    ]
                ])
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse these fixes
    }
};
