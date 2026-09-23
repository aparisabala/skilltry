<?php

function menuActive(string ...$patterns): string
{
    return request()->is(...$patterns) ? 'mm-active' : '';
}


function uploadsDir()
{
    return [
        'uploads',
        'uploads/' . config('i.service_domain'),
        'uploads/' . config('i.service_domain') . '/summernote',
        "uploads/app/" . config('i.service_domain') . "/dyn",
        "uploads/app/" . config('i.service_domain') . "/dyn/images",
    ];
}

function imagePaths()
{
    return [
        'summernote' => 'uploads/app/' . config('i.service_domain') . '/summernote/',
        "dyn_image" => "uploads/app/" . config('i.service_domain') . "/dyn/images/",
    ];
}

function imageExists($row,$ext='80X80'){
    $path = imagePaths()['dyn_image'].'/'.$row?->image.'_'.$ext.'.'.$row?->extension;
    return ($row?->image == null || !file_exists($path)) ? false : true;
}

function getRowImage($row,$ext='80X80'){
    $path = imagePaths()['dyn_image'].'/'.$row?->image.'_'.$ext.'.'.$row?->extension;
    $img = ($row?->image == "" || !file_exists($path)) ? url('images/system/img.jpg') : url($path);
    return $img;
}

if (! function_exists('pxLang')) {
    function pxLang($key='',$value='',$common='') {
        return app(\App\Services\PxCommandService::class)->pxLang($key,$value,$common);
    }
}

function getPolicyKey($Str,$key) {
    return $Str::lower($Str::replace(' ','_',$key));
}

function getYearList($startYear, $endYear)
{
    return range($startYear, $endYear);
}

function getMonthList()
{
    return [
        1  => 'January',
        2  => 'February',
        3  => 'March',
        4  => 'April',
        5  => 'May',
        6  => 'June',
        7  => 'July',
        8  => 'August',
        9  => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];
}

function getDayList()
{
    return [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
    ];
}
