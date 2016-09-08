<?php

// defines if the App is in development mode or not
define('DEV_MODE', true);

// defines if App will redirect wrong requests to default controller and action
define('REDIRECT_TO_DEFAULT', true);

// default properties
define('DEFAULT_CONTROLLER', 'DefaultController');
define('DEFAULT_ACTION', 'IndexAction');

// naming conventions
define('CONTROLLER_SUFFIX','Controller');
define('CONTROLLER_ACTION_SUFFIX','Action');
define('CONTROLLER_FILE_EXTENSION','.php');
define('MODEL_FILE_EXTENSION','.php');

// default hash salt for encryption methods
define('DEFAULT_HASH_SALT', 'thissomesecretmagicword');

// default user access role
define('DEFAULT_ACCESS_ROLE', 'anonymous');

// folder structure options
define('MODELS_DIR', '../processor/models/');
define('CONTROLLERS_DIR', '../processor/controllers/');
define('VIEWS_DIR', '../processor/views/');

// database (MYSQL)
define('DB_MYSQL_HOST', '127.0.0.1');
define('DB_MYSQL_PORT', 8889);
define('DB_MYSQL_USER', 'root');
define('DB_MYSQL_PASSWORD', 'root');
define('DB_MYSQL_DATABASE', 'mcvtest');
