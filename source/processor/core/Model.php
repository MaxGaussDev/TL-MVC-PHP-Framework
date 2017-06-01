<?php


class Model
{
    #region BASIC PROPERTIES

    protected $id;
    protected $hash;
    protected $createdAt;
    protected $updatedAt;

    // in case of soft delete
    protected $deletedAt;

    #endregion

    #region CONSTRUCTOR

    public function __construct()
    {

    }

    #endregion


    #region BASIC GETTERS AND SETTERS

    public function getId()
    {
        return (int)$this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        if (is_string($createdAt)){
            $this->createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt);
        }else{
            $this->createdAt = $createdAt;
        }
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        if (is_string($updatedAt)){
            $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $updatedAt);
        }else{
            $this->updatedAt = $updatedAt;
        }
    }

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt)
    {
        if (is_string($deletedAt)){
            $this->deletedAt = DateTime::createFromFormat('Y-m-d H:i:s', $deletedAt);
        }else{
            $this->deletedAt = $deletedAt;
        }
    }

    #endregion

    #region DATABASE METHODS

    public function save()
    {
        $this->createdAt = new DateTime('now');
        $this->updatedAt = new DateTime('now');
        $this->hash = Security::generateToken(32);

        $rb = R::dispense($this->tableName());
        foreach (get_class_methods($this) as $method) {
            if(Ralph::containsPrefix($method, 'get')){
                $rb->{lcfirst(str_replace('get', '', $method))} = $this->{$method}();
            }
        }
        $rb->id = 0;
        $this->id = R::store($rb);
    }

    public function update()
    {
        $this->updatedAt = new DateTime('now');
        $rb = R::dispense($this->tableName());
        foreach (get_class_methods($this) as $method) {
            if(Ralph::containsPrefix($method, 'get')){
                $rb->{lcfirst(str_replace('get', '', $method))} = $this->{$method}();
            }
        }
        $this->id = R::store($rb);
    }

    public function count()
    {
        return R::count($this->tableName());
    }

    public function findById($id)
    {
        $rb = R::load($this->tableName(), $id);
        if (!$rb->id) {
            return null;
        }else{
            $properties_array = $rb->getProperties();
            foreach ($properties_array as $key => $value) {
                $call = $this->createFunctionNameFromTableName($key, 'set');
                $this->{$call}($value);
            }
            return $this;
        }
    }

    public function findByHash($hash)
    {
        return $this->findFirst('hash = ?', array($hash));
    }

    public function findFirst($query_string = '', $values_array = array())
    {
        $rb = R::findOne($this->tableName(), $query_string, $values_array);
        if (!$rb->id) {
            return null;
        }else{
            $properties_array = $rb->getProperties();
            foreach ($properties_array as $key => $value) {
                $call = $this->createFunctionNameFromTableName($key, 'set');
                $this->{$call}($value);
            }
            return $this;
        }
    }

    public function findLast($query_string = '', $values_array = array())
    {
        $rb = R::findLast($this->tableName(), $query_string, $values_array);
        if (!$rb->id) {
            return null;
        }else{
            $properties_array = $rb->getProperties();
            foreach ($properties_array as $key => $value) {
                $call = $this->createFunctionNameFromTableName($key, 'set');
                $this->{$call}($value);
            }
            return $this;
        }
    }

    public function findAll($query_string = '', $values_array = array())
    {
       $rb_array = R::find($this->tableName(), $query_string, $values_array);
       if (!$rb_array || count($rb_array) == 0) {
           return array();
       }else{
           $results = array();
           foreach ($rb_array as $rb) {
               $properties_array = $rb->getProperties();
               foreach ($properties_array as $key => $value) {
                   $call = $this->createFunctionNameFromTableName($key, 'set');
                   $this->{$call}($value);
               }
               array_push($results, clone $this);
           }
           return $results;
       }
    }

    public function remove()
    {
        $rb = R::load($this->tableName(), $this->id);
        if (!$rb->id){
            return false;
        }else{
            if(DB_MYSQL_FORCE_SOFT_DELETE == true){
                $this->deletedAt = new DateTime('now');
                $this->update();
            }else{
                R::trash($rb);
            }
            return true;
        }
    }

    #endregion

    #region PUBLIC HELPER METHODS

    public function convertToPublic()
    {
        $return_object = new stdClass();
        foreach (get_class_methods($this) as $method) {
            if(Ralph::containsPrefix($method, 'get')){
                $return_object->{lcfirst(str_replace('get', '', $method))} = $this->{$method}();
            }
        }
        return $return_object;
    }

    public function convertToJsonString()
    {
        return json_encode($this->convertFromCamelCase());
    }

    public function prepareForJson()
    {
        return $this->convertFromCamelCase();
    }

    public function pluralName()
    {
        return $this->tableName();
    }

    // bind data from regular std object (or array)
    // for example. when controller fetches JSON or POST data from request
    public function bindData($obj){
        if(!is_array($obj) && is_object($obj)){
            $obj = get_object_vars($obj);
        }else{
            // error, wrong object type is passed
            if(DEV_MODE == true) {
                $error_msg = 'Bind Data Method accepts only Array() or Objects as arguments';
                dlog($error_msg);
            }
        }
        foreach ($obj as $key => $value){
            $method = $this->createFunctionNameFromTableName($key,'set');
            if(method_exists($this, $method)){
                $this->{$method}($value);
                // TODO: check if this parameter is another model object and deal with it
            }else{
                // TODO: this parameter is not set in our model but was sent, what do do with it?
            }
        }
    }

    #region

    #region PRIVATE METHODS

    private function createFunctionNameFromTableName($name, $magic_method_name = null)
    {
        $words = explode('_',strtolower($name));
        $f_words = array();
        foreach($words as $word){
            array_push($f_words, ucfirst($word));
        }
        $f_name = implode("",$f_words);
        // magic method name like: get, set, is, has, etc...
        if(!$magic_method_name) {
            return $f_name;
        }else{
            return $magic_method_name.$f_name;
        }
    }

    private function createTableNameFromPropertyName($input) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    private function convertFromCamelCase()
    {
        $tmp = new stdClass();
        foreach (get_object_vars($this->convertToPublic()) as $key => $value){
            $tmp->{$this->createTableNameFromPropertyName($key)} = $value;
        }
        return $tmp;
    }

    private function tableName()
    {

        if(ALLOW_UNDERSCORES_IN_TABLE_NAMES == true){
            $table_name = $this->createTableNameFromPropertyName(get_class($this));
        }else{
            $table_name = strtolower(get_class($this));
        }

        // convert singular names to plural names
        if(AUTORESOLVE_PLURAL_NAMES == true){
            if(strtolower(substr($table_name, -1)) != 's' || strtolower(substr($table_name, -3)) != 'ies' ){
                if(strtolower(substr($table_name, -1)) == 'y'){
                    $table_name = str_replace("y", "ies", $table_name);
                }else{
                    if(strtolower(substr($table_name, -1)) == 'x' || strtolower(substr($table_name, -2)) == 'ch'){
                        $table_name = $table_name.'es';
                    }else{
                        $table_name = $table_name.'s';
                    }
                }
            }
        }

        return $table_name;
    }

    #endregion

}