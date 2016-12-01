<?php

/*
 * Robotic Assistant for Logic and Headache
 *
 * Ok, so it's just a class filled with helper methods and nothing else, but at least we have to name it
 * somehow.
 */

class Ralph
{

    // check if string contains prefix (case-sensitive)
    public static function containsPrefix($string, $prefix)
    {
        $length = strlen($prefix);
        return (substr($string, 0, $length) === $prefix);
    }

    // check if string contains suffix (case-sensitive)
    public static function containsSuffix($string, $suffix)
    {
        $length = strlen($suffix);
        if ($length == 0) {
            return true;
        }
        return (substr($string, -$length) === $suffix);
    }

    // creates normalized string for url (a.k.a. slug)
    public static function sanitize($string)
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

    // sorts array of objects by parameter (case-sensitive)
    public static function sortObjectsArrayByKey(&$array,$key,$string = false,$asc = true){
        if($string){
            usort($array,function ($a, $b) use(&$key,&$asc)
            {
                if($asc)    return strcmp(strtolower($a->$key), strtolower($b->$key));
                else        return strcmp(strtolower($a->$key), strtolower($b->$key));
            });
        }else{
            usort($array,function ($a, $b) use(&$key,&$asc)
            {
                if($a->$key == $b->$key){return 0;}
                if($asc) return ($a->$key < $b->$key) ? -1 : 1;
                else     return ($a->$key > $b->$key) ? -1 : 1;

            });
        }
    }



}
