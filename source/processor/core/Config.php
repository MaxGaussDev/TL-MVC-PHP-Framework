<?php

// defines if the App is in development mode or not
define('DEV_MODE', true);

// autoresolve routes, if set to false, checks ROUTES config, otherwise checks for automatic MVC route patterns
define('AUTORESOLVE_ROUTES', false);
define('ROUTES_CONFIG_FILE', 'processor/core/Router.php');

// security reasons, don't allow request methods that are not defined in routes array (see: Router.php)
define('FORCE_ROUTE_SECURITY', true);

// defines if App will redirect wrong requests to default controller and action
define('REDIRECT_TO_DEFAULT', false);

// define if the server error responses will be outputted in html or string
// only works if DEV_MODE is set to false
define('REDIRECT_RESPONSE_ERRORS', true);

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

// file upload settings
define('DEFAULT_UPLOADS_DIRECTORY', 'public/uploads/');
define('DEFAULT_UPLOADS_FILE_TYPES', 'jpg, jpeg, png, gif, bmp, pdf');
define('DEFAULT_UPLOADS_FILE_FORCE_TYPES', true);

// folder structure options
define('MODELS_DIR', 'processor/models/');
define('CONTROLLERS_DIR', 'processor/controllers/');
define('VIEWS_DIR', 'processor/views/');

// database (MYSQL)
define('DB_MYSQL_HOST', '127.0.0.1');
define('DB_MYSQL_PORT', 3306);
define('DB_MYSQL_USER', 'root');
define('DB_MYSQL_PASSWORD', 'root');
define('DB_MYSQL_DATABASE', 'test_mvc');

// force soft delete in database models
define('DB_MYSQL_FORCE_SOFT_DELETE', false);


// console options:
// removes 's' at the end of the model class name
define('AUTORESOLVE_PLURAL_NAMES', true);

// allow undersocres in DB table names, disabled by default for ORM
define('ALLOW_UNDERSCORES_IN_TABLE_NAMES', false);

