<?php
namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminsSeeder extends Seeder
{
    public function run()
    {

       Admin::class::create([
            'name' => 'Jordan Nabigon',
            'email' => 'jordan@shared.com',
            'role' => 1,
            'is_super' => true,
            'password' => 'admin#1'
        ]);

        Admin::class::create([
            'name' => 'Brian Lapp',
            'email' => 'brian@freebies.com',
            'role' => 1,
            'is_super' => true,
            'password' => 'admin#1'
        ]);

        Admin::class::create([
            'name' => 'Mike Debutte',
            'email' => 'mike@freebies.com',
            'role' => 1,
            'is_super' => true,
            'password' => 'admin#1'
        ]);
    }
}
