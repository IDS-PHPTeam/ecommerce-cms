<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            [
                'country_code' => 'LB',
                'country_name_en' => 'Lebanon',
                'country_name_ar' => 'لبنان',
            ],
            [
                'country_code' => 'AE',
                'country_name_en' => 'United Arab Emirates',
                'country_name_ar' => 'الإمارات العربية المتحدة',
            ],
            [
                'country_code' => 'SA',
                'country_name_en' => 'Saudi Arabia',
                'country_name_ar' => 'المملكة العربية السعودية',
            ],
            [
                'country_code' => 'JO',
                'country_name_en' => 'Jordan',
                'country_name_ar' => 'الأردن',
            ],
            [
                'country_code' => 'KW',
                'country_name_en' => 'Kuwait',
                'country_name_ar' => 'الكويت',
            ],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['country_code' => $country['country_code']],
                $country
            );
        }
    }
}
