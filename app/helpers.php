<?php


use App\Models\UserDepartment\Discipline;
use Carbon\Carbon;
use Illuminate\Http\Request;


if (! function_exists('generate_random_password'))
{
    function generate_random_password()
    {
        $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
        return substr($random, 0, 10);
    }
}


if (! function_exists('make_options_for_ui_select'))
{
    function make_options_for_ui_select($array)
    {
        return collect($array)->map(function ($item, $key) {
            return [
                'id' => $key,
                'name' => $item
            ];
        })->toArray();
    }
}

if (! function_exists('make_options_from_simple_array'))
{
    function make_options_from_simple_array($values)
    {
        $options = [];

        if(is_array($values)) {
            foreach ($values as $value) {
                $options[$value] = $value;
            }
        }

        return $options;
    }
}


if (! function_exists('forget_element_in_array_by_id'))
{
    function forget_element_in_array_by_id($array, $elementId)
    {
        foreach ($array as $key => $value){
            if($value['id'] === $elementId){
                unset($array[$key]);
            }
        }

        return $array;
    }
}

if (! function_exists('get_owner_record_id_from_request'))
{
    function get_owner_record_id_from_request(Request $request)
    {
        return $request->input('serverMemo.dataMeta.models.ownerRecord.id');
    }
}


if (! function_exists('bytesToHuman'))
{
    function bytesToHuman($bytes)
    {
        $units = ['Байт', 'Кб', 'Мб', 'Гб', 'Тб'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}




if (! function_exists('get_random_numbers'))
{
    function get_random_numbers($min, $max, $total): array
    {
        $temp_arr = array();
        while(sizeof($temp_arr) < $total) $temp_arr[] = rand($min, $max);
        return $temp_arr;
    }
}


