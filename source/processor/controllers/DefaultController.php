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
            $im->setDescription("Image description");
            $im->uploadAndSave($file);
        }*/


        //render  view to frontend
        $this->renderView('example/index', array("value" => "You are now ready to drink the blood of infants."));

        // return json response
        //$this->returnJson(array("value" => "You are now ready to drink the blood of infants."));

    }
}