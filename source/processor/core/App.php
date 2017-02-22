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
        if(AUTORESOLVE_ROUTES == true) {
            // auto resolve routes with pattern /controller/action/params...
            $route_components_array = $this->parseRoute();

            // checking for the controller file
            if (file_exists(CONTROLLERS_DIR . ucfirst($route_components_array[0]) . CONTROLLER_SUFFIX . CONTROLLER_FILE_EXTENSION)) {
                $this->controller = ucfirst($route_components_array[0]) . CONTROLLER_SUFFIX;
                unset($route_components_array[0]);
                // require the controller file if found
                require_once CONTROLLERS_DIR . $this->controller . CONTROLLER_FILE_EXTENSION;
                // set the controller object as current controller
                $this->controller = new $this->controller;

                //checking controller action call
                if (isset($route_components_array[1])) {
                    if (method_exists($this->controller, ucfirst($route_components_array[1]) . CONTROLLER_ACTION_SUFFIX)) {
                        // set the method as a call action
                        $this->method = ucfirst($route_components_array[1]) . CONTROLLER_ACTION_SUFFIX;
                        unset($route_components_array[1]);
                    } else {
                        // throw error if the controller method is not found
                        if (DEV_MODE == true) {
                            $error_msg = 'Method "' . ucfirst($route_components_array[1]) . '' . CONTROLLER_ACTION_SUFFIX . '" not found in ' . get_class($this->controller) . ' Class';
                            dlog($error_msg);
                        }
                    }
                }

                // checking for any passed arguments
                // set empty array if none found
                $this->parameters = $route_components_array ? array_values($route_components_array) : array();

                // actually call the damn thing and pass parameters
                call_user_func_array(array($this->controller, $this->method), $this->parameters);

            } else {
                // throw error if controller file does not exist
                if (DEV_MODE == true) {
                    $error_msg = 'The Controller file: "' . ucfirst($route_components_array[0]) . '' . CONTROLLER_SUFFIX . CONTROLLER_FILE_EXTENSION . '" was not found';
                    dlog($error_msg);
                }
            }
        }else{
            // skip auto resolve routes, and check config.php for ROUTES const.
            if(file_exists(ROUTES_CONFIG_FILE)){
                // if everything is set, require main routes file
                require_once ROUTES_CONFIG_FILE;
                if (class_exists('Router')) {
                    $router = new Router();
                    $route_components_array = $router->resolveRoute($this->parseRoute());
                    if($route_components_array != false){
                        if (file_exists(CONTROLLERS_DIR.$route_components_array['controller'].CONTROLLER_FILE_EXTENSION)){
                            $this->controller = $route_components_array['controller'];
                            require_once CONTROLLERS_DIR . $this->controller . CONTROLLER_FILE_EXTENSION;
                            $this->controller = new $this->controller;
                            //checking controller action call
                            if (isset($route_components_array['action'])) {
                                if (method_exists($this->controller, $route_components_array['action'])) {
                                    $this->method = $route_components_array['action'];
                                    // actually call the damn thing and pass parameters
                                    if(isset($route_components_array['parameters'])){
                                        $this->parameters = $route_components_array['parameters'];
                                    }
                                    // passing on the parameters with their names defined in routes
                                    call_user_func_array(array($this->controller, $this->method), $this->parameters);
                                }else{
                                    if (DEV_MODE == true) {
                                        $error_msg = 'Method: <b>'.$route_components_array['action']. '</b> not found in: '.CONTROLLERS_DIR.$route_components_array['controller'].CONTROLLER_FILE_EXTENSION;
                                        dlog($error_msg);
                                    }
                                }
                            }else{
                                if (DEV_MODE == true) {
                                    $error_msg = 'No Method found for Route: '.$_GET['route'];
                                    dlog($error_msg);
                                }
                            }
                        }
                    }else{
                        if (DEV_MODE == true) {
                            $error_msg = 'No route found for: /'.$_GET['route'];
                            dlog($error_msg);
                        }
                    }
                }else{
                    if (DEV_MODE == true) {
                        $error_msg = 'Could not find Router Class in: '.ROUTES_CONFIG_FILE.' file.';
                        dlog($error_msg);
                    }
                }
            }else{
                // no router.php file found
                if (DEV_MODE == true) {
                    $error_msg = 'The Routes configuration file not set.';
                    dlog($error_msg);
                }
            }
        }
    }

    // route parser
    protected function parseRoute()
    {
        if(isset($_GET['route'])){
            if(AUTORESOLVE_ROUTES == true) {
                return explode('/', filter_var(rtrim($_GET['route'], '/')), FILTER_SANITIZE_URL);
            }else{
                return $_GET['route'];
            }
        }
    }

}