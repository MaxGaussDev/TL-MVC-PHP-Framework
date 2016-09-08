<?php

class Security
{
    // custom encryption goes here
    public static function encrypt($string){
        // example, change this any way you see fit
        return md5(sha1(base64_encode($string).DEFAULT_HASH_SALT.strrev($string)));
    }

    // managing access tokens for current user and roles
    // using session as storage
    public static function setAccess($role = DEFAULT_ACCESS_ROLE){

        $access_object = new stdClass();
        $access_object->token = md5((string)time());
        $access_object->role = $role;

        $_SESSION['access-token'] = $access_object;
    }

    public static function removeAccess(){
        unset($_SESSION['access-token']);
    }

    public static function getAccess(){
        if(isset($_SESSION['access-token'])){
            return $_SESSION['access-token'];
        }else{
            return false;
        }
    }


}