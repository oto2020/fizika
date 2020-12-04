<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        // Владелец сайта:
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Конюхова Антонина Евгеньевна',
            'avatar_src' => '/storage/img/AVATAR_ZAYAC.png',
            'user_role_id' => 1,  // admin
            'school_id' => 1, // base
            'class_name' => null,
            'email' => 'admin@gmail.com',
            'verified_at' => null,
            'password' => Hash::make('1234'), // ПАРОЛЬ УКАЗАН В КАЧЕСТВЕ ПРИМЕРА
            'remember_token' => null,
            'created_at' => now(),
            'updatet_at' => null,
        ]);

        // Демо-аккаунт владельца сайта
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Блыщик Владимир Фёдорович',
            'avatar_src' => '/storage/img/AVATAR_ZAYAC.png',
            'user_role_id' => 1,  // admin
            'school_id' => 1, // base
            'class_name' => null,
            'email' => 'demo@gmail.com',
            'verified_at' => null,
            'password' =>  Hash::make('1234'), // ПАРОЛЬ УКАЗАН В КАЧЕСТВЕ ПРИМЕРА
            'remember_token' => null,
            'created_at' => now(),
            'updatet_at' => null,
        ]);
    }
}
