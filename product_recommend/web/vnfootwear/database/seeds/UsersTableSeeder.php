<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed superadmin
        User::create([
            'username' => 'vxuandai',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
            'name' => 'Đại Vương',
            'email' => 'vxuandai@gmail.com',
            'role' => 'superadmin'
        ]);

        // Seed customers
        $path = "/home/lam/Desktop/vini-intern/product_recommend/web/vnfootwear/database/seeds/data/users_sample_data.csv";
        $csv = array();
        $lines = file($path, FILE_IGNORE_NEW_LINES);

        foreach ($lines as $key => $value)
        {
            $csv[$key] = str_getcsv($value);
        }

        for ($i=1; $i<count($csv); $i++) {
            $name = $csv[$i][0];
            User::create([
                'username' => strtolower($name),
                'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
                'name' => $name,
                'email' => strtolower($name) . '@gmail.com',
                'role' => 'customer'
            ]);
        }
    }
}
