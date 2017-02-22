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