<?php

// Main Routes configuration file

Class Router
{
    // route parameters:
    //  controller          - what controller will be called
    //  action              - controller public method that will be called
    //  methods             - array of supported request methods, if not set all methods will be allowed
    //  menu_item_index     - useful for automatically selecting menu tabs (see: Ralph.php line: 77)
    //  roles               - array of supported user roles that have access to the route, if not set all roles will be allowed

    protected $routes = array(
        "/" => array(
            "controller" => "DefaultController",
            "action" => "IndexAction"
        ),
        "/test" => array(
            "controller" => "DefaultController",
            "action" => "TestAction"
        ),
        "/test/with/:username/:age" => array(
            "controller" => "DefaultController",
            "action" => "AnotherAction"
        )
    );

    public function resolveRoute($route_string){

        $this->checkForRoutesFile();
        // first we check the simple routes without parameters
        $key_chk = '/'.$route_string;
        if(isset($this->routes[$key_chk])){
            return $this->routes[$key_chk];
        }else{
            //if not found immediately, perform global search
            $match = false;
            foreach($this->routes as $key => $val){
                $r_chk = $this->compareRoutes($key_chk, $key);
                if($r_chk){
                    $match = true;
                    // pass the parameters pulled form URI
                    if(is_array($r_chk)){$this->routes[$key]['parameters'] = $r_chk;}
                    return $this->routes[$key];
                }
            }
            if(!$match){return false;}
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

        // if there are any parameters to be found on the way, this is where we store them
        $params_array = array();

        if (count($route_array) == count($definition_array)){
            foreach($definition_array as $def_index_val){
                // check for optional parameter
                if($def_index_val[0] == ':'){
                    $index = array_search($def_index_val,$definition_array);
                    // pass parameters if found
                    $definition_array[$index] = ltrim($definition_array[$index], ':');
                    $params_array[$definition_array[$index]] = $route_array[$index];
                    unset($definition_array[$index]);
                    unset($route_array[$index]);
                }
            }
            if(count(array_diff($route_array, $definition_array)) != 0){
                $match = false;
            }
        }else{ $match = false; }
        if($match){
            // everything went ok
            if(!empty($params_array)){return $params_array;}else{return true;}
        }else{
            // route not found
            return false;
        }
    }

    private function checkForRoutesFile($type = 'php'){
        if(file_exists(__DIR__.'/_routes.'.$type)){
            if($type == 'php'){
                require_once __DIR__.'/_routes.'.$type;
                $this->routes = array_merge($this->routes, _routes::$routes);
            }
        }
    }

    public function getAllRoutes()
    {
        $this->checkForRoutesFile();
        return $this->routes;
    }
}

