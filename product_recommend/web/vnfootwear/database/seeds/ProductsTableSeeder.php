<?php

use Illuminate\Database\Seeder;
use App\Product;
// use Illuminate\Support\Arr;
// use Webpatser\Uuid\Uuid;
// use JD\Cloudder\Facades\Cloudder as Cloudder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed products
        $path = "/home/lam/Desktop/vini-intern/product_recommend/web/vnfootwear/database/seeds/data/products_sample_data.csv";
        $csv = array();
        $lines = file($path, FILE_IGNORE_NEW_LINES);

        foreach ($lines as $key => $value)
        {
            $csv[$key] = str_getcsv($value);
        }

        // remove label
        array_shift($csv);
        // remove product loss props
        // $filtered = Arr::where($csv, function ($value, $key) {
        //     return count($value) > 2;
        // });


        $categories = ['football', 'running', 'originals', 'training'];
        $genres = ['kids', 'women', 'men'];

        // dd($csv[0]);

        for ($i=0; $i<count($csv); $i++) {
            $product_name = $csv[$i][0];
            $product_code = $csv[$i][2];
            $product_price = $csv[$i][3];
            $product_category = $categories[rand(0, 3)];
            $product_genre = $genres[rand(0, 2)];
            $image_id = $csv[$i][4];
            
            Product::create([
                'image' => $image_id,
                'name' => $product_name,
                'category' => $product_category,
                'genre' => $product_genre,
                'code' => $product_code,
                'size' => '22,23,24',
                'color' => '#ED2E2E,#2DB438,#2168AA',
                'price' => $product_price
            ]);
        }
    }
}
