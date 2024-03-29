<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            ['name' => 'акушерства и гинекологии'],
            ['name' => 'акушерства и гинекологии ИНМФО'],
            ['name' => 'амбулаторной и скорой медицинской помощи'],
            ['name' => 'анатомии человека'],
            ['name' => 'анестезиологии и реаниматологии, трансфузиологии и скорой медицинской помощи Института НМФО'],
            ['name' => 'биологии '],
            ['name' => 'биотехнических систем и технологий'],
            ['name' => 'внутренних болезней'],
            ['name' => 'внутренних болезней Института НМФО'],
            ['name' => 'гистологии, эмбриологии, цитологии'],
            ['name' => 'госпитальной терапии, ВПТ'],
            ['name' => 'госпитальной хирургии'],
            ['name' => 'дерматовенерологии'],
            ['name' => 'детских болезней'],
            ['name' => 'детских болезней педиатрического факультета'],
            ['name' => 'детских инфекционных болезней'],
            ['name' => 'детской хирургии'],
            ['name' => 'иммунологии и аллергологии'],
            ['name' => 'иностранных языков с курсом латинского языка'],
            ['name' => 'инфекционных болезней с эпидемиологией и тропической медициной'],
            ['name' => 'истории и культурологии'],
            ['name' => 'кардиологии, сердечно-сосудистой и торакальной хирургии Института НМФО'],
            ['name' => 'клинической лабораторной диагностики'],
            ['name' => 'клинической фармакологии и интенсивной терапии'],
            ['name' => 'лучевой диагностики'],
            ['name' => 'лучевой, функциональной и лабораторной диагностики Института НМФО'],
            ['name' => 'медико-социальных технологий с курсом педагогики и образовательных технологий дополнительного профессионального образования'],
            ['name' => 'медицинской реабилитации и спортивной медицины'],
            ['name' => 'медицины катастроф'],
            ['name' => 'микробиологии, вирусологии, иммунологии с курсом клинической микробиологии'],
            ['name' => 'молекулярной биологии и генетики'],
            ['name' => 'неврологии, нейрохирургии, медицинской генетики'],
            ['name' => 'неврологии, психиатрии, мануальной медицины и медицинской реабилитации Института НМФО'],
            ['name' => 'нормальной физиологии'],
            ['name' => 'общей гигиены и экологии'],
            ['name' => 'общей и клинической психологии'],
            ['name' => 'общей хирургии с курсом урологии'],
            ['name' => 'общественного здоровья и здравоохранения'],
            ['name' => 'общественного здоровья и здравоохранения Института НМФО'],
            ['name' => 'онкологии'],
            ['name' => 'онкологии, гематологии и трансплантологии ИНМФО'],
            ['name' => 'оперативной хирургии и топографической анатомии'],
            ['name' => 'ортодонтии'],
            ['name' => 'ортопедической стоматологии с курсом клинической стоматологии'],
            ['name' => 'ортопедической стоматологии и ортодонтии Института НМФО'],
            ['name' => 'оториноларингологии'],
            ['name' => 'офтальмологии'],
            ['name' => 'патологической анатомии'],
            ['name' => 'патофизиологии, клинической патофизиологии'],
            ['name' => 'педиатрии и неонатологии Института НМФО'],
            ['name' => 'пропедевтики стоматологических заболеваний'],
            ['name' => 'профессиональных болезней'],
            ['name' => 'профильных гигиенических дисциплин'],
            ['name' => 'психиатрии, наркологии и психотерапии'],
            ['name' => 'русского языка и социально-культурной адаптации'],
            ['name' => 'стоматологии детского возраста'],
            ['name' => 'стоматологии Института НМФО'],
            ['name' => 'судебной медицины'],
            ['name' => 'теоретической биохимии с курсом клинической биохимии'],
            ['name' => 'терапевтической стоматологии'],
            ['name' => 'травматологии, ортопедии и ВПХ'],
            ['name' => 'управления и экономики фармации, медицинского и фармацевтического товароведения'],
            ['name' => 'факультетской терапии'],
            ['name' => 'факультетской хирургии'],
            ['name' => 'фармакогнозии и ботаники'],
            ['name' => 'фармакологии и биоинформатики'],
            ['name' => 'фармакологии и фармации Института НМФО'],
            ['name' => 'фармацевтической и токсикологической химии'],
            ['name' => 'фармацевтической технологии и биотехнологии'],
            ['name' => 'физики, математики и информатики'],
            ['name' => 'физической культуры и здоровья'],
            ['name' => 'философии, биоэтики и права с курсом социологии медицины'],
            ['name' => 'фтизиопульмонологии'],
            ['name' => 'фундаментальной медицины и биологии'],
            ['name' => 'химии'],
            ['name' => 'хирургических болезней №1 Института НМФО'],
            ['name' => 'хирургических болезней №2 Института НМФО'],
            ['name' => 'хирургической стоматологии и челюстно-лицевой хирургии'],
            ['name' => 'экономики и менеджмента'],
        ]);
    }
}
