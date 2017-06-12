<?php

class Controller
{

    // load model
    // depricated, but still works for older versions
	protected function loadModel($model)
    {
        if(file_exists(MODELS_DIR.ucfirst($model).MODEL_FILE_EXTENSION)){
            require_once MODELS_DIR.ucfirst($model).MODEL_FILE_EXTENSION;
            return new $model();
        }else{
            // throw error if model file does not exist
            if(DEV_MODE == true) {
                $error_msg = 'The Model file: "' . ucfirst($model) . '.php" was not found';
                dlog($error_msg);
            }
            return null;
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
                dlog($error_msg);
            }
            //trow_not_found_response_error();
        }
    }
	
    // redirect to route
    protected function redirectToRoute($route)
    {
        $host = $_SERVER['HTTP_HOST'];
        $scheme = $_SERVER['REQUEST_SCHEME'];
        $base_uri = str_replace('/index.php','',$_SERVER['SCRIPT_NAME']);
        $redirect_uri = $host.$base_uri.$route;
        header("Location: {$scheme}://{$redirect_uri}");
    }

    // get request method
    protected function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    // get json request body data
    protected function getJSON()
    {
        if(Ralph::containsPrefix($_SERVER['CONTENT_TYPE'], 'application/json') || Ralph::containsPrefix($_SERVER['CONTENT_TYPE'], 'text/plain')){
            return (object)json_decode(file_get_contents('php://input'), true);
        }else{
            return false;
        }
    }

    // get request files bag
    protected function getRequestFiles($key = null)
    {
        if(!$key){
            return $_FILES;
        }else{
            return $_FILES[$key];
        }
    }

    // get post parameters only
    protected function getPost(){

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
    }

    // get request parameters (still in experimental phase)
    protected function getRequestParameters()
    {
        $data = array();

        // request parse data - test later, get the body of delete, put, patch req.
        //parse_str(file_get_contents("php://input"),$post_vars);
        //echo $post_vars['fruit']." is the fruit\n";

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
        foreach($_GET as $key => $value){
            // checking for additional parameters and removing Tiny's route from GET
            if(!empty($_GET[$key])){
                if($key != 'route'){
                    $data[$key] = $value;
                }
            }
        }
        return (object)$data;
    }

    // return json response
    protected function returnJson($data = null, $message = null, $code = 200)
    {
        http_response_code($code);
        header('Content-type: application/json;charset=utf-8');

        //header('Access-Control-Allow-Headers : origin, content-type, accept');
        //header('Access-Control-Allow-Origin : *');
        //header('Access-Control-Allow-Methods : POST, GET, PUT, PATCH, DELETE');

        if($data != null){
            //header('ETag : '.md5(time()));
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
