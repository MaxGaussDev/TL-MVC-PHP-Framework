# Tiny Lord MVC Framework for PHP
TL is a small and bare bones MVC framework for PHP, it has a bit more than usual skeleton frameworks, but it is designed to be small and flexible, but also easy to use. 

# Minimum Requirements
- PHP v5.4.32+
- Rewrite Mod needs to be enabled

# Importing and making it work
To import TL MVC, copy the content of the source folder into your project root directory. In the root .htaccess file edit the following line:
```
RewriteBase /TL-MVC-PHP-Framework/source
```
Change RewriteBase value with the name of your project directory. Or set up .htaccess as you see fit for your own needs or project structure. The "source" directory was designed with the idea of actually being the root directory (like htdocs, or www), so renaming it with your project name won't influence the framework structure. 

To test if the TL MVC is propperly set up, check the url: http://your-host/your-project-name/ (if you kept the source folder in the project structure the url should be: http://your-host/your-project-name/source/)

If the setup was successful you should see the following message:
```
Default view... 
some example value to pass on to the view
```
# Project Structure
TL MVC is pretty much flexible about it's project structure, but there are a few mandatory rules. This is how the basic structure should look like at it's core:
```
.
├── index.php
├── .htaccess
├── processor
│   ├── controllers
│   │   └── DefaultController.php
│   ├── core
│   │   ├── App.php
│   │   ├── Config.php
│   │   ├── console
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   ├── Functions.php
│   │   ├── Model.php
│   │   ├── Router.php
│   │   └── Security.php
│   ├── init.php
│   ├── models
│   └── views
│       └── example
│           └── index.phtml
└── public
    └── css
        └── main.css

```
So far the only mandatory rule is to keep the "core" files and folders inside the "processor" folder along with the init.php file. You can edit the Config.php file and alter the models, views and controllers files and folder structure, it is recommended to keep them in the "processor" directory under their default names. Your css, javascript and other public files, should be kept in the "public" folder.

# Working with TL MVC default structure

Here are some mandatory rules:
- Controller files must have the same names as controller classes they contain, by default both need to have "Controller" sufix (see Config.php if you wish to change it)
- Model files must have the same names as model classes they contain
- Routing controller methods are recognized by their sufix, by default it is set to "Action" (see Config.php if you wish to change it)
- Routing methods need to be public
- Controller and Model classes need to extend their parent classes accordingly

Basic Model Class example (filename: "Default.php"):
```
<?php
class Default extends Model
{

}
```

Basic Controller Class example (filename: "DefaultController.php"):
```
<?php
class DefaultController extends Controller
{
    public function IndexAction
    {
        // responds to route: http://host/project-name/default/index
        
        //loading a model object
        $model = $this->loadModel('ModelName');
    }
}
```
If you want TL to hadle the routing automatically, you should change the AUTORESOLVE_ROUTES to true (see Config.php):
```
define('AUTORESOLVE_ROUTES', true);
```
If enabled, routes will be handled automatically following this pattern:
```
http://<your-host>/<your-project-name>/<controller-name-without-sufix>/<action-name-without-sufix>/<argument1>/<argument2>/...
```

# Custom routes
This option is set up by default. All routes are defined in the "Router.php" file. router is a class, so if you wish, you can handle the routes configuration in a seperate file if you need to. There are a couple of examples already set up in advance to work with th DefaultController:
```
protected $routes = array(
        "/" => array(
            "controller" => "DefaultController",
            "action" => "IndexAction"
        ),
        "/test" => array(
            "controller" => "DefaultController",
            "action" => "TestAction"
        ),
        "/test/with/:username/:age" => array(
            "controller" => "DefaultController",
            "action" => "AnotherAction"
        )
    );
```
Basically this is how you map your routes with their Controllers and actions. The keywords with ":" are dynamic and these are basically placeholders for your arguments or parameters you want to pass on to the controller. See DefaultController.php file for examples for each of these routes and how they are being handled.

# Database configuration
To set up the database go to: /processor/core/Config.php and edit the following constants per your own needs:
```
define('DB_MYSQL_HOST', '127.0.0.1');
define('DB_MYSQL_PORT', 8889);
define('DB_MYSQL_USER', 'root');
define('DB_MYSQL_PASSWORD', 'root');
define('DB_MYSQL_DATABASE', 'mcvtest');
```

# Connecting to Database
Currently, all your queries to the database should be done inside the model classes (but this is not mandatory), here is the example:
```
$result = Database::doQuery('..your mysql query');
```
All your security checks should be done before calling this function.

# View rendering
View rendering is done inside of the controller. Views are .phtml files and there is no mandatory folder or file structure required for them. Example for view rendering:
```
//render  view to frontend
$this->renderView('path/to/view/filename-without-extension', array("value" => "some example value to pass on to the view"));
```
All data passed to the view will be stored inside the "data" array.

# JSON Response
Any controller can return JSON response. 
```
// return json response
$this->returnJson();
```
By default if no data is passed, the response is just an default response with 200 OK status code:
```
{
  "status_code": 200
}
```

You can also pass arrays or objects, set a log message and status code which will be returned in the reposnse header:
```
$data = array(
    "foo" => "bar",
    "other" => array(
        "other_data" => "some value"
    )
);
$this->returnJson($data, "log message if needed", 200);
```

# Console
TL has a bare bones and very basic Command Line tool, to access it use:
```
$ cd path-to-project/source/core
$ sudo php console help
```
Most of the available functions will be displayed with the help command. Tiny's console can setup project files, map MySQL database from the Config.php file and generate some core MVC files along the way. It will not rewrite exsisting MVC files if there are any.

# Development mode
Development mode is enabled by default in the Config.php file. If enabled it will return error messages in the response (for example if there are no defined routes, if some MVC files are missing, some Database exceptions etc.).

# Ralph
Ralph is a helper class that contains static methods to make life easier. For example, you can user Ralph to find sufixes or prefixes of a string, sort object arrays, search object arrays etc. This class is updated every now and then with new methods. Ralph is already included in init.php loader and it is accessible from about anywhere.
```
$slug = Ralph::sanitize('Some string That needs to be slugified');
```
# DLog
Dlog is a debug log function that you can use inside your MVC structure or any PHP file inside your source directory. 
```
// example - outputing a message
$msg = "some error message or string";
dlog($msg);

// example - dumping an object or array
$object = new stdClass;
$object->some_property = 'some value';

dlog($object);
```
The output will show dumped values and a simple stack trace. If you have a development mode set to true, a simple example can be viewed when calling an unexistent route in the browser:
```
DLog - App :: __construct :: Line - 5
Called from :: /var/www/html/TLMVC/source/processor/core/App.phpData 

Stack Trace: 
/var/www/html/TLMVC/source/processor/core/App.php (Line: 98)
/var/www/html/TLMVC/source/index.php (Line: 5)

Data Dump:
No route found for: /some/unexistent/route
```

# The end, for now...
So far, that's it. 

TL MVC does have it's own Console, more configuration options and some additional helper functions like user authorization, encryption, database mapping, file generators, etc. but these are still in development (feel free to play around with them) and some of them are not done. I will update the documentation as I continue to update the code. The whole idea was to make something bare bones and simple to build upon, but with enough flexibility .
