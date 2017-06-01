<?php

class Msg
{
    public static function getErr($key, $lang){
        
        // error messages main array
        $messages_array = array(
            "method_not_allowed" => array(
                "hr" => "Request metoda nije dozvoljena.",
                "en" => "Request method not allowed."
            ),
            "missing_parameters" => array(
                "hr" => "Nisu poslani svi parametri.",
                "en" => "Missing parameters."
            ),
            "general_error_msg" => array(
                "hr" => "Došlo je do greške.",
                "en" => "Something went wrong."
            ),
            "not_found" => array(
                "hr" => "Nije pronađen niti jedan zapis.",
                "en" => "No records found."
            ),
            "not_allowed" => array(
                "hr" => "Ova akcija nije dopuštena.",
                "en" => "Action not allowed."
            )
        );
        
        return $messages_array[$key][$lang];
    }
    
    public static function getMsg($key, $lang){
        
        // ok messages main array
        $messages_array = array(
            "file_uploaded" => array(
                "hr" => "Datoteka je spremljena.",
                "en" => "File uploaded."
            ),
            "updated" => array(
                "hr" => "Ažurirano.",
                "en" => "Updated."
            ),
            "found" => array(
                "hr" => "Pronađeni rezultati upita.",
                "en" => "Found."
            ),
            "removed" => array(
                "hr" => "Zapis je obrisan.",
                "en" => "Removed."
            ),
            "created" => array(
                "hr" => "Zapis je napravljen.",
                "en" => "Created."
            )
        );
        
        return $messages_array[$key][$lang];
    }
}
 