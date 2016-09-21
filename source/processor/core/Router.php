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
        $key = '/'.$route_string;
        if(isset($this->routes[$key])){
            return $this->routes[$key];
        }else{
            return false;
        }
    }

    private function compareRoutes($current_rt, $defined_rt){

        // compares current route with some other defined route
        // example:
        // route defined in $this->routes           "/path/action/:parameter/:argument";
        // actual route being handled right now:    "/path/action/username/madmax";

        $definition_array = explode('/', filter_var(rtrim($defined_rt, '/')), FILTER_SANITIZE_URL);
        $route_array = explode('/', filter_var(rtrim($current_rt, '/')), FILTER_SANITIZE_URL);

        unset($route_array[0]);
        unset($definition_array[0]);
        $match = true;

        if (count($route_array) == count($definition_array)){
            // do other stuff, like compare values and shit
            foreach($definition_array as $def_index_val){
                // if index_val is not optional, check if it matches with current route
                if($def_index_val[0] == ':'){
                    $index = array_search($def_index_val,$definition_array);
                    unset($definition_array[$index]);
                    unset($route_array[$index]);
                }
            }
            if(count(array_diff($route_array, $definition_array)) != 0){
                $match = false;
            }
        }else{ $match = false; }
        if($match){ return true; }else{ return false; }
    }

}

