<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Product::unguard();
        \App\Price::unguard();

        $this->call(ProductTableSeeder::class);
        $this->call(PriceTableSeeder::class);

        \App\Product::reguard();
        \App\Price::reguard();
    }
}
