<?php

// Only contains public functions for development environment
// like: dumps and such...

function dlog($value, $dump = false){

    $trace = debug_backtrace();
    echo "<pre style='font-family: monospace; background-color: black; color: darkslategray; padding: 10px;'>";

    if (isset($trace[1])) {
        echo "<span style='font-size: 16px;'>DLog - {$trace[1]['class']} :: {$trace[1]['function']} :: Line - {$trace[1]['line']}</span><br>";
    }
    echo "<span style='font-size: 12px;'>Called from :: {$trace[0]['file']}</span><hr style='border-color: darkslategray;'>";

    if(count($trace)>1){
        echo "<span>Data Stack Trace: </span><br>";
        foreach ($trace as $t){
            echo "<span style='font-size: 12px;'>{$t['file']} (Line: {$t['line']})</span><br>";
        }
        echo "<hr style='border-color: darkslategray;'>";
    }

    echo "<span>Data Dump:</span><br><br>";
    if(!$dump){
        print_r($value);
    }else{
        var_dump($value);
    }
    echo "</pre>";
    die();
}

function throw_not_found_response_error($message = null, $template = true){
    throw_response_error(404, 'Not found', $message, $template);
}

function throw_unauthorised_response_error($message = null, $template = true){
    throw_response_error(401, 'Unauthorised', $message, $template);
}

function throw_service_unavailable_response_error($message = null, $template = true){
    throw_response_error(503, 'Service Unavailable', $message, $template);
}

function throw_forbidden_response_error($message = null, $template = true){
    throw_response_error(403, 'Forbidden', $message, $template);
}

function throw_response_error($code = 500, $error_msg = "Internal Server Error", $message = null, $template = true){
    header("{$_SERVER['SERVER_PROTOCOL']} {$code} {$error_msg}");
    if(!REDIRECT_RESPONSE_ERRORS){ $template = false; }

    // TODO: check if browser request or not and change response to fit the request

    if($template){
        // checking for request with JSON content type, if true return json
        if(Ralph::containsPrefix($_SERVER['CONTENT_TYPE'], 'application/json')){
            header('Content-type: application/json;charset=utf-8');
            $cnt = array();
            $cnt["code"] = $code;
            $cnt["error"] = $error_msg;
            if($message){$cnt["user_message"] = $message;}
            echo json_encode($cnt);
            exit;
        }
        if(file_exists(VIEWS_DIR.'errors/'.$code.'.phtml')){
            if($message){ define('RESPONSE_ERROR_MSG', $message); }
            require_once VIEWS_DIR.'errors/'.$code.'.phtml';
        }
    }else{
        if($message){
            echo $message;
        }else{
            echo $error_msg;
        }
    }
    exit;
}