<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Size Attribute
        $sizeAttribute = Attribute::create([
            'name' => 'Size',
            'slug' => Str::slug('Size'),
            'description' => 'Product size attribute',
            'status' => 'active',
        ]);

        $sizeValues = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        foreach ($sizeValues as $index => $value) {
            AttributeValue::create([
                'attribute_id' => $sizeAttribute->id,
                'value' => $value,
                'sort_order' => $index,
            ]);
        }

        // Color Attribute
        $colorAttribute = Attribute::create([
            'name' => 'Color',
            'slug' => Str::slug('Color'),
            'description' => 'Product color attribute',
            'status' => 'active',
        ]);

        $colorValues = ['Red', 'Blue', 'Green', 'Yellow', 'Black', 'White', 'Gray', 'Brown'];
        foreach ($colorValues as $index => $value) {
            AttributeValue::create([
                'attribute_id' => $colorAttribute->id,
                'value' => $value,
                'sort_order' => $index,
            ]);
        }
    }
}




