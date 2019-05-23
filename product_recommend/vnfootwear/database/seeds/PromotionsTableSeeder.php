<?php

use Illuminate\Database\Seeder;
use App\Promotion;
use Webpatser\Uuid\Uuid;

class PromotionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $discounts = [
            '10', 
            '20', 
            '30', 
            '40',
            '50',
            '60',
            '70',
            '80',
            '90'
        ];   
        for ($i=0; $i<100; $i++) {
            $code = (string) Uuid::generate(4);
            $discount = $discounts[rand(0, 8)];
            
            Promotion::create([
                'code' => $code,
                'discount' => $discount
            ]);
        }
    }
}
