<?php

// Main Routes configuration file

Class Router
{
    protected $routes = array(
        "/" => array(
            "controller" => "DefaultController",
            "action" => "IndexAction"
        ),
        "/test" => array(
            "controller" => "DefaultController",
            "action" => "TestAction"
        )
    );

    public function resolveRoute($route_string){
        //echo "route string for checking root dir: ".$route_string."<br>";
        $key = '/'.$route_string;
        //echo "key for route: ".$key;
        //print_r($this->routes[$key]);
        
        if(isset($this->routes[$key])){
            return $this->routes[$key];
        }else{
            return false;
        }
    }
}

