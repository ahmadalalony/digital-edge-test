<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use Illuminate\Support\Str;

class CountriesCitiesSeeder extends Seeder
{

    public function run(): void
    {
        $countries = [
            [
                'name' => 'United Arab Emirates',
                'iso' => 'AE',
                'slug' => 'uae',
                'cities' => ['Abu Dhabi', 'Dubai', 'Sharjah', 'Ajman', 'Ras Al Khaimah', 'Fujairah', 'Umm Al Quwain'],
            ],
            [
                'name' => 'Syria',
                'iso' => 'SY',
                'slug' => 'syria',
                'cities' => ['Damascus', 'Aleppo', 'Homs', 'Latakia'],
            ]

        ];

        foreach ($countries as $c) {
            $country = Country::firstOrCreate(
                ['slug' => $c['slug']],
                ['name' => $c['name'], 'iso_code' => $c['iso']]
            );

            foreach ($c['cities'] as $cityName) {
                $country->cities()->firstOrCreate(
                    ['name' => $cityName],
                    ['slug' => Str::slug($cityName)]
                );
            }
        }
    }

}
