<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Article;
use App\Models\Menu;
use App\Models\SiteConfig;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MinimalWorkingSeeder extends Seeder
{
    /**
     * Run the database seeds to create a minimal working application.
     */
    public function run(): void
    {
        // Create admin user if not exists
        if (!Admin::where('email', 'admin@example.com')->exists()) {
            Admin::create([
                'name' => 'Admin User',
                'slug' => 'admin-user',
                'email' => 'admin@example.com',
                'role' => 1,
                'is_super' => true,
                'password' => Hash::make('password')
            ]);
        }
        
        // Create required site config entries
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
        
        // Create a sample menu item if none exists
        if (Menu::count() === 0) {
            Menu::create([
                'title' => 'Home',
                'url' => '/',
                'order' => 1,
                'auth_only' => false,
                'is_beta' => false
            ]);
        }
        
        // Create a sample article if none exists
        if (Article::count() === 0) {
            Article::create([
                'title' => 'Welcome to Free Parent Search',
                'slug' => 'welcome',
                'content' => '<p>This is a sample article to get you started.</p>',
                'admin_id' => Admin::first()->id,
                'is_featured' => true,
                'is_page' => false,
                'status' => 1
            ]);
        }
    }
}
