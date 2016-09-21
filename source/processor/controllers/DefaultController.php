<?php

class DefaultController extends Controller
{

    public function IndexAction()
    {
        //load model example
        //$model = $this->loadModel('Example');

        //render  view to frontend
        $this->renderView('example/index', array("value" => "some example value to pass on to the view"));

        // return json response
        //$this->returnJson();

    }

    public function TestAction()
    {
        echo "test action in the default controller";
    }

    public function AnotherAction($username, $age)
    {
        echo "Username: {$username} <br>";
        echo "Username: {$age} <br>";
    }

}