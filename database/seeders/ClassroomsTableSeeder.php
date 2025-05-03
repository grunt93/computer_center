<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomsTableSeeder extends Seeder
{
    public function run(): void
    {
        $classrooms = [
            ['code' => 'A220'],
            ['code' => 'A221'],
            ['code' => 'A301'],
            ['code' => 'A302'],
            ['code' => 'A303'],
            ['code' => 'A304'],
            ['code' => 'A305'],
            ['code' => 'A306'],
            ['code' => 'A307'],
            ['code' => 'A310'],
            ['code' => 'A311'],
            ['code' => 'A314'],
            ['code' => 'A315'],
            ['code' => 'A316'],
            ['code' => 'A319'],
            ['code' => 'A521'],
            ['code' => 'A522'],
            ['code' => 'A525'],
            ['code' => 'A526'],
            ['code' => 'A603'],
            ['code' => 'A604'],
            ['code' => 'A609'],
            ['code' => 'A610'],
            ['code' => 'A611'],
            ['code' => 'A617'],
            ['code' => 'A618'],
            ['code' => 'A619'],
            ['code' => 'A620'],
            ['code' => 'A621'],
            ['code' => 'A728'],
            ['code' => 'A813'],
            ['code' => 'A818'],
            ['code' => 'A823'],
            ['code' => 'A824'],
            ['code' => 'A827'],
            ['code' => 'A828'],
            ['code' => 'A917'],
            ['code' => 'A918'],
            ['code' => 'A920'],
            ['code' => 'A921'],
            ['code' => 'A923'],
            ['code' => 'C307'],
            ['code' => 'C308'],
            ['code' => 'D301'],
            ['code' => 'D401'],
            ['code' => 'D402'],
            ['code' => 'D403A'],
            ['code' => 'D403B'],
            ['code' => 'D407'],
            ['code' => 'D408'],
            ['code' => 'D409'],
            ['code' => 'D416'],
            ['code' => 'D417'],
            ['code' => 'D418'],
            ['code' => 'D502'],
            ['code' => 'D504A'],
            ['code' => 'D504B'],
            ['code' => 'D508'],
            ['code' => 'D510'],
            ['code' => 'D619'],
            ['code' => 'E501B'],
            ['code' => 'E502'],
            ['code' => 'E504'],
            ['code' => 'L408'],
            ['code' => 'L501'],
            ['code' => 'L502'],
            ['code' => 'L901'],
            ['code' => 'L902'],
            ['code' => 'L903'],
            ['code' => 'L910'],
        ];

        foreach ($classrooms as $classroom) {
            DB::table('classrooms')->insert([
                'code' => $classroom['code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}