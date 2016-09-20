<?php

// Main Routes configuration file

Class Router
{
    protected $routes = array(
        "/home" => array(
            "controller" => "DefaultController",
            "action" => "IndexAction"
        ),
        "/test" => array(
            "controller" => "DefaultController",
            "action" => "TestAction"
        )
    );

    public function resolveRoute($route_string){
        $key = '/'.$route_string;
        if(isset($this->routes[$key])){
            return $this->routes[$key];
        }else{
            return false;
        }
    }
}

