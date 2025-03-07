<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'name' => 'United States',
                'code' => 'US',
            ],
            [
                'name' => 'Canada',
                'code' => 'CA',
            ],
        ];

        foreach ($countries as $country) {
            $query = collect($country)
                ->only(['code'])
                ->all();
            Country::updateOrCreate($query, $country);
        }
    }
}
