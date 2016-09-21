<?php

class DefaultController extends Controller
{

    public function IndexAction()
    {
        //load model example
        //$model = $this->loadModel('Example');

        //render  view to frontend
        //$this->renderView('example/index', array("value" => "some example value to pass on to the view"));

        // return json response
        $this->returnJson();

    }

    public function TestAction()
    {
        echo "test action in the default controller";
    }

    public function AnotherAction($username, $age)
    {
        echo "Another action in the Default controller<br>Data passed:";
        print_r($username);
        print_r($age);
    }

}