<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Product;
use App\Promotion;
use App\Order;
use App\OrderDetail;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            ProductsTableSeeder::class,
            PromotionsTableSeeder::class,
            OrdersTableSeeder::class,
        ]);
    }
}
