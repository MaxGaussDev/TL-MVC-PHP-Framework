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

    // returns object from array of objects by property values
    // ex: ($objects_array_name, 'property-name', $property_value)
    public static function findObjectInArrayByPropertyValue($array, $index, $value)
    {
        foreach($array as $arrayInf) {
            if($arrayInf->{$index} == $value) {
                return $arrayInf;
            }
        }
        return null;
    }

    // mark main menu item as active
    public static function itemMenuIndex($item_index_number){
        // check router for menu_item_index property in defined routes
        if(defined("MENU_ITEM_INDEX")){
            if(MENU_ITEM_INDEX == $item_index_number){
                //change this if needed
                // this will be used by css usually to mark selected menu page
                echo "class=\"active\"";
            }
        }
    }

    // returns array from converted multidimensional object
    public static function objToArray($obj)
    {
        // Not an object or array
        if (!is_object($obj) && !is_array($obj)) {
            return $obj;
        }

        // Parse array
        foreach ($obj as $key => $value) {
            $arr[$key] = Ralph::objToArray($value);
        }

        // Return parsed array
        return $arr;
    }

    // returns difference between two multidimensional arrays as an array
    public static function compareArrays($array1, $array2){
        $result = array();

        foreach($array1 as $key => $val) {
            if(is_array($val) && isset($array2[$key])) {
                $tmp = Ralph::compareArrays($val, $array2[$key]);
                if($tmp) {
                    $result[$key] = $tmp;
                }
            }
            elseif(!isset($array2[$key])) {
                $result[$key] = null;
            }
            elseif($val !== $array2[$key]) {
                $result[$key] = $array2[$key];
            }

            if(isset($array2[$key])) {
                unset($array2[$key]);
            }
        }

        $result = array_merge($result, $array2);

        return $result;
    }

    // returns difference between two json strings as json string
    public static function compareJson($json1, $json2){
        $data1 = json_decode($json1, true);
        $data2 = json_decode($json2, true);

        $data1 = Ralph::objToArray($data1);
        $data2 = Ralph::objToArray($data2);

        $data = Ralph::compareArrays($data1, $data2);
        $array = Ralph::cleanEmptyArrayElemets($data);

        return json_encode($array);
    }

    // removes empty elements from an array
    public static function cleanEmptyArrayElemets($data){

        $array = array_map('array_filter', $data);
        $array = array_filter($array);

        return json_encode($array);
    }
}
