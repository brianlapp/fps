<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Menu;
use App\Models\SiteConfig;
use Database\Seeders\SimpleArticle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds to create test data for UI development.
     */
    public function run(): void
    {
        // Use existing admin user or create a new one
        $admin = Admin::first();
        
        if (!$admin) {
            $admin = Admin::create([
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
        
        // Create sample menu items
        if (Menu::count() === 0) {
            $menuItems = [
                [
                    'name' => 'Home',
                    'link' => '/',
                    'order' => 1,
                    'auth_only' => false,
                    'is_beta' => false
                ],
                [
                    'name' => 'Resources',
                    'link' => '/resources',
                    'order' => 2,
                    'auth_only' => false,
                    'is_beta' => false
                ],
                [
                    'name' => 'About',
                    'link' => '/about',
                    'order' => 3,
                    'auth_only' => false,
                    'is_beta' => false
                ],
                [
                    'name' => 'Contact',
                    'link' => '/contact',
                    'order' => 4,
                    'auth_only' => false,
                    'is_beta' => false
                ],
                [
                    'name' => 'My Account',
                    'link' => '/account',
                    'order' => 5,
                    'auth_only' => true,
                    'is_beta' => false
                ]
            ];
            
            foreach ($menuItems as $item) {
                Menu::create($item);
            }
        }
        
        // Create sample articles
        if (SimpleArticle::count() === 0) {
            $adminId = $admin->id;
            
            $articles = [
                [
                    'title' => 'Welcome to Free Parent Search',
                    'slug' => 'welcome',
                    'content' => '<p>Welcome to Free Parent Search, your resource for finding support and information for parents.</p><p>Our mission is to connect parents with the resources they need to thrive.</p>',
                    'author_id' => $adminId,
                    'is_featured' => true,
                    'is_page' => false
                ],
                [
                    'title' => 'Finding Local Resources',
                    'slug' => 'local-resources',
                    'content' => '<p>There are many local resources available to parents. Here are some tips for finding them in your area.</p><p>Start by checking with your local community center, library, or school district.</p>',
                    'author_id' => $adminId,
                    'is_featured' => true,
                    'is_page' => false
                ],
                [
                    'title' => 'Parenting Tips',
                    'slug' => 'parenting-tips',
                    'content' => '<p>Parenting can be challenging, but these tips can help make it easier.</p><p>Remember to take care of yourself, establish routines, and communicate openly with your children.</p>',
                    'author_id' => $adminId,
                    'is_featured' => false,
                    'is_page' => false
                ],
                [
                    'title' => 'About Us',
                    'slug' => 'about',
                    'content' => '<p>Free Parent Search was founded with the mission of helping parents find the resources they need.</p><p>Our team is dedicated to providing up-to-date information and support for parents everywhere.</p>',
                    'author_id' => $adminId,
                    'is_featured' => false,
                    'is_page' => true
                ],
                [
                    'title' => 'Contact Us',
                    'slug' => 'contact',
                    'content' => '<p>Have questions or suggestions? We\'d love to hear from you!</p><p>You can reach us by email at info@freeparentsearch.com or by phone at (555) 123-4567.</p>',
                    'author_id' => $adminId,
                    'is_featured' => false,
                    'is_page' => true
                ]
            ];
            
            foreach ($articles as $article) {
                SimpleArticle::create($article);
            }
        }
    }
}
