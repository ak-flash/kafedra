<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('faculties')->insert([
            ['name' => 'Лечебный','speciality' => 'Лечебное дело', 'tag' => 'Леч', 'color' => 'green'],
            ['name' => 'Педиатрический', 'speciality' => 'Педиатрия', 'tag' => 'Пед', 'color' => 'purple'],
            ['name' => 'Медико-биологический','speciality' => 'Медицинская биохимия', 'tag' => 'МБФ', 'color' => 'blue'],
            ['name' => 'Стоматологический', 'speciality' => 'Стоматология', 'tag' => 'Стом', 'color' => 'red'],
            ['name' => 'Лечебный', 'speciality' => 'Медико-профилактическое дело', 'tag' => 'МПД', 'color' => 'gray'],
            ['name' => 'Фармацевтический', 'speciality' => 'Фармация', 'tag' => 'Фарм', 'color' => 'pink'],
            ['name' => 'Факультет социальной работы и клинической психологии', 'speciality' => 'Клиническая психология', 'tag' => 'КП', 'color' => 'yellow'],
            ['name' => 'Факультет социальной работы и клинической психологии', 'speciality' => 'Социальная работа', 'tag' => 'СоцР', 'color' => 'yellow'],
            ['name' => 'Факультет социальной работы и клинической психологии', 'speciality' => 'Менеджмент', 'tag' => 'Менедж', 'color' => 'yellow'],
            ['name' => 'Факультет социальной работы и клинической психологии', 'speciality' => 'Педагогическое образование', 'tag' => 'ПедОбр', 'color' => 'yellow'],
            ['name' => 'Медико-биологический','speciality' => 'Биотехнические системы и технологии', 'tag' => 'БСТ', 'color' => 'indigo'],
            ['name' => 'Медико-биологический','speciality' => 'Биология', 'tag' => 'Биологи', 'color' => 'indigo'],
            ]);
    }
}
