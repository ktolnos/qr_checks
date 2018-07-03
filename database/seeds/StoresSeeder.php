<?php

use Illuminate\Database\Seeder;

class StoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stores')->insert([[
            'inn' => '7703270067',
            'name' => 'Ашан'
        ],[
            'inn' => '2310031475',
            'name' => 'Магнит'
        ],[
            'inn' => '7704218694',
            'name' => 'Metro'
        ],[
            'inn' => '5047076050',
            'name' => 'IKEA дом'
        ]
        ]);
    }
}
