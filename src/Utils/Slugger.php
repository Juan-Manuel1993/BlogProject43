<?php
namespace App\Utils;

class Slugger
{
    public function slugify($string)
    {
        $string = preg_replace('~[^\pL\d]+~u', '-', $string);

        $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);

        $string = preg_replace('~[^-\w]+~', '', $string);

        $string = trim($string, '-');

        $string = preg_replace('~-+~', '-', $string);
        
        $string = strtolower($string);
        if (empty($string)) {
            return 'n-a';
        }
        return $string;
    }
}
