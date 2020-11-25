<?php

use Illuminate\Database\Seeder;

class SectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sections')->insert([
            'id' => 1,
            'name' => '7 класс',
            'url' => '7-class',
            'idSchool' => 1
        ]);
        DB::table('sections')->insert([
            'id' => 2,
            'name' => '8 класс',
            'url' => '8-class',
            'idSchool' => 1
        ]);
        DB::table('sections')->insert([
            'id' => 3,
            'name' => '9 класс',
            'url' => '9-class',
            'idSchool' => 1
        ]);
        DB::table('sections')->insert([
            'id' => 4,
            'name' => '10 класс',
            'url' => '10-class'
        ]);
        DB::table('sections')->insert([
            'id' => 5,
            'name' => '11 класс',
            'url' => '11-class',
            'idSchool' => 1
        ]);
    }
}
