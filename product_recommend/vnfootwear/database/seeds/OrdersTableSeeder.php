<?php

use Illuminate\Database\Seeder;
use App\Order;
use App\OrderDetail;
use App\User;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed orders
        $path = "/home/lam/Desktop/vini-intern/product_recommend/web/vnfootwear/database/seeds/data/orders_sample_data.csv";
        $csv = array();
        $lines = file($path, FILE_IGNORE_NEW_LINES);

        foreach ($lines as $key => $value)
        {
            $csv[$key] = str_getcsv($value);
        }

        // remove label
        array_shift($csv);

        $sizes = ['22','23','24'];
        $colors = ['#ED2E2E','#2DB438','#2168AA'];

        // dd($csv[0]);

        for ($i=0; $i<count($csv); $i++) {
            $order_data = $csv[$i];
            $user_id = $order_data[1];
            $user = User::find($user_id);
            $name = $user->name;
            $order = new Order;
            $order->user_id = $user_id;
            $order->name = $name;
            $order->phone = '0375509533';
            $order->address = 'Hanoi';
            $order->promotion_id = null;
            $order->save();

            $order_detail_datas = explode(',', $order_data[2]);
            foreach($order_detail_datas as $order_detail_data) {
                $order_detail = new OrderDetail;
                $order_detail->product_id = $order_detail_data;
                $order_detail->order_id = $order->id;
                $order_detail->quantity = 1;
                $order_detail->size = $sizes[rand(0, 2)];
                $order_detail->color = $colors[rand(0, 2)];
        
                $order_detail->save();
            } 
        }
    }
}
