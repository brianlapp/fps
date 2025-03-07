<?php

namespace Database\Seeders;

use App\Models\SiteConfig;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default code injections if not exists
        if (!DB::table('site_config')->where('key', 'injections')->exists()) {
            SiteConfig::createByKey('injections', [
                'header' => '',
                'footer' => ''
            ]);
        }

        // Create default social settings if not exists
        if (!DB::table('site_config')->where('key', 'social_settings')->exists()) {
            SiteConfig::createByKey('social_settings', [
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
            ]);
        }
    }
}
