<?php

class App
{

    // default controller - see config
    protected $controller = DEFAULT_CONTROLLER;
    // default method - see config
    protected $method = DEFAULT_ACTION;

    // method arguments default, if none set to empty array
    protected $parameters = array();

    // Application main constructor
    public function __construct()
    {
        $route_components_array = $this->parseRoute();

        // checking for the controller file
        if(file_exists(CONTROLLERS_DIR.ucfirst($route_components_array[0]).CONTROLLER_SUFFIX.CONTROLLER_FILE_EXTENSION)){
            $this->controller = ucfirst($route_components_array[0]).CONTROLLER_SUFFIX;
            unset($route_components_array[0]);
            // require the controller file if found
            require_once CONTROLLERS_DIR.$this->controller.CONTROLLER_FILE_EXTENSION;
            // set the controller object as current controller
            $this->controller = new $this->controller;

            //checking controller action call
            if(isset($route_components_array[1])){
                if(method_exists($this->controller, ucfirst($route_components_array[1]).CONTROLLER_ACTION_SUFFIX)){
                    // set the method as a call action
                    $this->method = ucfirst($route_components_array[1]).CONTROLLER_ACTION_SUFFIX;
                    unset($route_components_array[1]);
                }else{
                    // throw error if the controller method is not found
                    if(DEV_MODE == true) {
                        $error_msg = 'Method "' . ucfirst($route_components_array[1]) .''.CONTROLLER_ACTION_SUFFIX.'" not found in ' . get_class($this->controller) . ' Class';
                        die($error_msg);
                    }
                }
            }

            // checking for any passed arguments
            // set empty array if none found
            $this->parameters = $route_components_array ? array_values($route_components_array) : array();

            // actually call the damn thing and pass parameters
            call_user_func_array(array($this->controller, $this->method), $this->parameters);

        }else{
            // throw error if controller file does not exist
            if(DEV_MODE == true){
                $error_msg = 'The Controller file: "'.ucfirst($route_components_array[0]).'"'.CONTROLLER_SUFFIX.CONTROLLER_FILE_EXTENSION.'" was not found';
                die($error_msg);
            }
        }
    }

    // route parser
    protected function parseRoute()
    {
        if(isset($_GET['route'])){
           return explode('/', filter_var(rtrim($_GET['route'], '/')), FILTER_SANITIZE_URL);
        }
    }
}