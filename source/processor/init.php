<?php

require_once 'core/Functions.php';
require_once 'core/Ralph.php';
require_once 'core/Config.php';
require_once 'core/Security.php';
require_once 'core/App.php';
require_once 'core/Database.php';
require_once 'core/rb.php';
require_once 'core/Msg.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';

// database setup for ORM
R::setup('mysql:host='.DB_MYSQL_HOST.':'.DB_MYSQL_PORT.';dbname='.DB_MYSQL_DATABASE, DB_MYSQL_USER, DB_MYSQL_PASSWORD);

if(ALLOW_UNDERSCORES_IN_TABLE_NAMES == true) {
    // in case we need to use underscores in table names
    // this is restricted by the ORM, it uses _ for table relations
    // see core/Config.php ALLOW_UNDERSCORES_IN_TABLE_NAMES
    R::setStrictTyping(false);
}

if(DEV_MODE == false) {
    // set redbean to freeze mode if not in development environment
    R::freeze(true);
}

// load all models, a.k.a. poorman's autoloader for models
$model_files = scandir(MODELS_DIR);
foreach ($model_files as $mfile){
    if(pathinfo($mfile)['extension'] == 'php'){
        require_once MODELS_DIR.$mfile;
    }
}