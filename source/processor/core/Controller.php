<?php

class Controller
{
    // load model
	protected function loadModel($model)
    {
        if(file_exists(MODELS_DIR.ucfirst($model).MODEL_FILE_EXTENSION)){
            require_once MODELS_DIR.ucfirst($model).MODEL_FILE_EXTENSION;
            return new $model();
        }else{
            // throw error if model file does not exist
            if(DEV_MODE == true) {
                $error_msg = 'The Model file: "' . ucfirst($model) . '.php" was not found';
                die($error_msg);
            }
        }
    }

    // render view with passed arguments
    protected function renderView($view, $data = array())
    {
        if(file_exists(VIEWS_DIR.$view.'.phtml')){
            require_once VIEWS_DIR.$view.'.phtml';
        }else{
            // throw error if model file does not exist
            if(DEV_MODE == true) {
                $error_msg = 'The View file: "' . $view . '.phtml" was not found';
                die($error_msg);
            }
        }
    }
	
    // redirect to route
    protected function redirectToRoute($route)
    {
        $host = $_SERVER['HTTP_HOST'];
        $base_uri = str_replace('/index.php','',$_SERVER['SCRIPT_NAME']);
        $redirect_uri = $host.$base_uri.$route;
        header("Location: http://{$redirect_uri}");
    }

    // get request method
    protected function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    // get json request body data
    protected function getJSON()
    {
        if($_SERVER['CONTENT_TYPE'] == 'application/json' || $_SERVER['CONTENT_TYPE'] == 'text/plain'){
            return (object)json_decode(file_get_contents('php://input'), true);
        }else{
            return false;
        }
    }

    // get request parameters
    protected function getRequestParameters()
    {
        $method = $this->getMethod();
        if($method == 'POST'){
            $data = array();
            foreach($_POST as $key => $value){
                // do some security stuff here for request params
                if(!empty($_POST[$key])){
                    if(strtolower($key) != 'email'){
                        $data[$key] = urlencode($value);
                    }else{
                        $data[$key] = $value;
                    }
                }
            }
            return (object)$data;
        }else{
            return false;
        }
    }

    // return json response
    protected function returnJson($data = null, $message = null, $code = 200)
    {
        http_response_code($code);
        header('Content-type: application/json');
        header('Access-Control-Allow-Headers : origin, content-type, accept');
        header('Access-Control-Allow-Origin : *');
        header('Access-Control-Allow-Methods : POST, GET');
        if($data != null){
            header('ETag : '.md5((string)$data));
        }
        $response_data = array(
            "status_code" => $code
        );
        if($message != null){
            $response_data['log_msg'] = $message;
        }
        if($data != null) {
            $response_data['content'] = $data;
        }
        echo json_encode($response_data);
    }

}
