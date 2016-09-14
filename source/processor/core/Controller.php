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

    protected function returnJson($data = null, $message = null, $code = 200){

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