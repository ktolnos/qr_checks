<?php

use Illuminate\Database\Seeder;

class ChecksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i<10; $i++) {
            DB::table('checks')->insert([
                'fiscalSign' => str_random(10),
                'fiscalDriveNumber' => str_random(10),
                'fiscalDocumentNumber' => str_random(10),
                'storeInn' => str_random(20),
                'initialTotalSum' => rand(20, 2000),
                'initialDate' => new DateTime('now'),
                'status' => 5,
            ]);
        }
    }
}
