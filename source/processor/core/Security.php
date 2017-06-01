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

    // flash message
    // using session as storage
    public static function setFlashMessage($msg_string){
        $_SESSION['flash-msg'] = $msg_string;
    }
    
    public static function showFlash(){
        if(isset($_SESSION['flash-msg'])){
            echo $_SESSION['flash-msg'];
            unset($_SESSION['flash-msg']);
        }
    }

    // file upload (to specific folder, public by default)
    public static function uploadFile($file, $path = null, $name = null)
    {
        // checking for file errors
        if($file['error'] != UPLOAD_ERR_OK){
            if (DEV_MODE == true) {
                $error_msg = 'Error: File \''.$file['name'].'\' Not uploaded. File error: '. Security::fileErrorCodeToMessage($file['error']);
                dlog($error_msg);
            }
            return false;
        }

        // checking for file path
        if($path == null){
            $path = DEFAULT_UPLOADS_DIRECTORY;
        }else{
            $path = DEFAULT_UPLOADS_DIRECTORY.$path;
        }

        // checking for file name
        if($name == null){
            $name = $file['name'];
        }

        // check if the file type is allowed for upload
        if(defined('DEFAULT_UPLOADS_FILE_FORCE_TYPES') && defined('DEFAULT_UPLOADS_FILE_TYPES')){
            if(DEFAULT_UPLOADS_FILE_FORCE_TYPES == true){
                // checking for '*' --> stands for all file types
                if(DEFAULT_UPLOADS_FILE_TYPES != '*'){
                    $allowed_file_types = explode(',',DEFAULT_UPLOADS_FILE_TYPES);
                    $type_supported = false;
                    foreach ($allowed_file_types as $type){
                        if(Ralph::containsSuffix( $file['type'], str_replace(' ', '', $type))){
                            $type_supported = true;
                        }
                    }
                    if(!$type_supported){
                        if (DEV_MODE == true) {
                            $error_msg = 'File type \''.$file['type'].'\' Not allowed for upload.';
                            dlog($error_msg);
                        }
                        return false;
                    }
                }
            }
        }

        // upload file to destination
        if (move_uploaded_file($file["tmp_name"], $path.$name)) {
            return $path.$name;
        } else {
            if (DEV_MODE == true) {
                $error_msg = 'Error: File \''.$path.$name.'\' Not uploaded.';
                dlog($error_msg);
            }
            return false;
        }
    }

    public static function uploadFileFromUrl($url, $path = null, $name = null)
    {

        // checking for file path
        if($path == null){
            $path = DEFAULT_UPLOADS_DIRECTORY;
        }else{
            $path = DEFAULT_UPLOADS_DIRECTORY.$path;
        }

        // checking for file name
        $file_data = exif_read_data($url);

        if($name == null){
            $name = $file_data['FileName'];
        }

        // check if the file type is allowed for upload
        if(defined('DEFAULT_UPLOADS_FILE_FORCE_TYPES') && defined('DEFAULT_UPLOADS_FILE_TYPES')){
            if(DEFAULT_UPLOADS_FILE_FORCE_TYPES == true){
                // checking for '*' --> stands for all file types
                if(DEFAULT_UPLOADS_FILE_TYPES != '*'){
                    $allowed_file_types = explode(',',DEFAULT_UPLOADS_FILE_TYPES);
                    $type_supported = false;
                    foreach ($allowed_file_types as $type){
                        if(Ralph::containsSuffix( $file_data['MimeType'], str_replace(' ', '', $type))){
                            $type_supported = true;
                        }
                    }
                    if(!$type_supported){
                        if (DEV_MODE == true) {
                            $error_msg = 'File type \''.$file_data['MimeType'].'\' Not allowed for upload.';
                            dlog($error_msg);
                        }
                        return false;
                    }
                }
            }
        }

        // checking to see if 'allow_url_fopen' extension is enabled
        if( ini_get('allow_url_fopen') ) {
            if (file_put_contents($path.$name, file_get_contents($url))) {
                return $path.$name;
            } else {
                if (DEV_MODE == true) {
                    $error_msg = 'Error: File \''.$url.'\' Not uploaded.';
                    dlog($error_msg);
                }
                return false;
            }
        }else{
            // if not we download the file via curl
            $ch = curl_init($url);
            $fp = fopen($path.$name, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            return $path.$name;
        }
    }

    // getting the base url
    public static function baseUrl()
    {
        $host = $_SERVER['HTTP_HOST'];
        $path = str_replace("index.php","",$_SERVER['PHP_SELF']);
        return 'http://'.$host.$path;
    }

    // email validation
    public static function validateEmail($string){
        $string = filter_var($string, FILTER_SANITIZE_EMAIL);
        if (!filter_var($string, FILTER_VALIDATE_EMAIL) === false) {
            return true;
        }else{
            return false;
        }
    }

    // generate random token hash string
    public static function generateToken($length = 64) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    // helper method for translating file error upload codes
    public static function fileErrorCodeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }

}
