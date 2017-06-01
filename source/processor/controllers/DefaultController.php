<?php


class DefaultController extends Controller
{

    public function IndexAction()
    {

        //load model example
        //$model = $this->loadModel('Image');

        // files upload example
        /*foreach ($this->getRequestFiles() as $file){
            $im = new Image();
            $im->setDescription("ovo je neki opis ove slike");
            $im->uploadAndSave($file);
        }*/

        //render  view to frontend
        $this->renderView('example/index', array("value" => "You are now ready to drink the blood of infants."));

        // return json response
        //$this->returnJson(array("value" => "You are now ready to drink the blood of infants."));

    }

    public function TestAction()
    {
       //echo "test action in the default controller";
       //print_r($this->getRequestParameters());die();
    }

    public function AnotherAction($username, $age)
    {
        echo "Username: {$username} <br>";
        echo "Age: {$age} <br>";
    }

}