<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['Женя','Богдан','Лада'];
        for ($i=0; $i<3; $i++) {
            DB::table('users')->insert([
                'name' => $names[$i],
                'email' => str_random(10).'@mail.com',
                'password' => str_random(10),
                'remember_token' => str_random(20),
            ]);
        }
    }
}
