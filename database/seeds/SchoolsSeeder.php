<?php

use Illuminate\Database\Seeder;

class SchoolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('schools')->insert([
            'id' => 1,
            'name' => 'Базовая школа',
            'url' => 'base',
            'fullName' => 'Базовая школа, содержащая базовый курс физики, от которого наследуются остальные школы.',
            'geoAddress' => 'г. Симферополь, проспект Вернадского, 4',
            'created_at' => now(),
            'updatet_at' => null,
        ]);
    }
}
