<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Country;
use App\Models\Offer;
use App\Models\OfferCategory;
use App\Models\Post;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offers:generate {count}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates bunch of fake posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $categoryIds = OfferCategory::query()->get()->pluck('id');
        $count = $this->argument('count');
        $faker = Factory::create();


        for ($i = 0; $i < $count; $i++) {
            $offer = new Offer();
            $offer->offer_category_id = $faker->randomElement($categoryIds);
            $offer->title =  str_replace('.', '', $faker->unique()->text(50));
            $offer->description = $faker->unique()->text(150);
            $offer->link = $faker->unique()->url();
            $offer->save();

            $image = $faker->image(null, 450, 375, null, true, true, $offer->title);
            $offer->addMedia($image)
                ->toMediaCollection('image');
        }
    }
}
