<?php

class Image extends Model
{

    #region PROPERTIES

    protected $name;
    protected $fileName;
    protected $path;
    protected $description;
    protected $height;
    protected $width;
    protected $size;
    protected $url;
    protected $type;

    #endregion


    #region GETTERS AND SETTERS

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    #endregion


    #region HELPER METHODS

    public function uploadAndSave($file, $filename = null, $path = null, $preserve_file_name = false)
    {
        // checking file type
        if(Ralph::containsPrefix($file['type'], 'image')){
            $this->type = $file['type'];
        }else{
            if(DEV_MODE == true) {
                $error_msg = 'The file type: "' . $file['type'] . '" is not image.';
                dlog($error_msg);
            }
            // TODO: handle error here, only images are to be supported
            throw_forbidden_response_error();
        }

        // checking for preset file name
        if (!$filename) {
            // keep the original file name, not recommended
            if($preserve_file_name){
                $this->fileName = $file['name'];
            }else{
                $this->fileName = Security::generateToken(32).'_'.$file['name'];
            }
        }else{
            // this name is forced by the user
            $this->fileName = $filename;
        }

        if(!$this->name){
            $this->name = $file['name'];
        }

        // get the file size and image dimensions
        $this->size = $file['size'];
        $image_info = getimagesize($file["tmp_name"]);
        if ($image_info){
            $this->width = $image_info[0];
            $this->height = $image_info[1];
        }

        // save image to directory given in path, or default
        $this->path = Security::uploadFile($file, $path, $this->fileName);
        if($this->path){
            $this->url = Security::baseUrl().$this->path;
            $this->save();
        }
    }

    public function delete(){
        if(file_exists($this->path)){
            if(!DB_MYSQL_FORCE_SOFT_DELETE){
                unlink($this->path);
            }
            return $this->remove();
        }else{
            if(DEV_MODE == true) {
                $error_msg = 'The file: "' . $this->path . '" not found.';
                dlog($error_msg);
            }
            // TODO: handle error here
            return false;
        }
    }

    public function returnBase64(){
        if(file_exists($this->path)) {
            return 'data:image/' . pathinfo($this->path, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($this->path));
        }else{
            return null;
        }
    }

    public function uploadAndSaveFromUrl($url, $filename = null, $path = null, $preserve_file_name = false){

        // fetch image data from url
        $data = exif_read_data($url);

        //prepare data before save
        $this->size = $data['FileSize'];
        $this->width = $data['COMPUTED']['Width'];
        $this->height = $data['COMPUTED']['Height'];

        // checking file type
        if(Ralph::containsPrefix($data['MimeType'], 'image')){
            $this->type = $data['MimeType'];
        }else{
            if(DEV_MODE == true) {
                $error_msg = 'The file type: "' . $data['MimeType'] . '" is not image.';
                dlog($error_msg);
            }
            // TODO: handle error here, only images are to be supported
            throw_forbidden_response_error();
        }

        if(!$this->name){
            $this->name = $data['FileName'];
        }

        // checking for preset file name
        if (!$filename) {
            // keep the original file name, not recommended
            if($preserve_file_name){
                $this->fileName = $data['FileName'];
            }else{
                $this->fileName = Security::generateToken(32).'_'.$data['FileName'];
            }
        }else{
            // this name is forced by the user
            $this->fileName = $filename;
        }

        // save image to directory given in path, or default
        $this->path = Security::uploadFileFromUrl($url, $path, $this->fileName);
        if($this->path){
            $this->url = Security::baseUrl().$this->path;
            $this->save();
        }
    }

    #endregion

}