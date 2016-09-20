# TL-MVC-PHP-Framework
Tiny Lord is small and bare bones MVC framework for PHP.

# Minimum Requirements
- PHP v5.4 or greater
- Rewrite Engine needs to be enabled

# Importing and Basic Setup
To import TL MVC, copy the source files into your project root directory. In the root .htaccess file edit the following line:
```
RewriteBase /source
```
Change "source" with the name of your project directory. Or set up .htaccess as you see fit for your own needs.

To set up the database go to: /processor/core/Config.php and edit the following constants per your own needs:
```
define('DB_MYSQL_HOST', '127.0.0.1');
define('DB_MYSQL_PORT', 8889);
define('DB_MYSQL_USER', 'root');
define('DB_MYSQL_PASSWORD', 'root');
define('DB_MYSQL_DATABASE', 'mcvtest');
```
To test if the TL MVC is propperly set up, check the url: http://your-host/your-project-name/default/index

If the setup was successful you should see the following message:
```
Default view... 
some example value to pass on to the view
```

# Project Structure
TL MVC is pretty much flexible about it's project structure, but there are a few mandatory rules. This is how the basic structure should look like at it's core:
```
/project-name
    /processor
        /core
        init.php
        .htaccess
    /public
        index.php
    .htaccess  
```
Even though you can edit the Config.php file and alter the models, views and controllers files and folder structure, it is recommended to keep them in the "processor" directory under their default names. Your css, javascript and other public files, should be kept in the "public" folder.

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
Routing is generated automatically following this pattern:
```
http://<your-host>/<your-project-name>/<controller-name-without-sufix>/<action-name-without-sufix>/<argument1>/<argument2>/...
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
# Routing
By default, TinyLord will use this pattern 'http://host/controller/action/parameters' for any http call. However if you really need your own custom routes set up, go to the Config.php file in the 'core' folder, and edit this line of code:
```
define('AUTORESOLVE_ROUTES', true);
```
Set AUTORESOLVE_ROUTES to false. This will tell the bootstrap file to look for Router.php class and routes configuration. For example purposes, Router.php file has some routes already set up, for example:

```
protected $routes = array(
        "/home" => array(
            "controller" => "DefaultController",
            "action" => "IndexAction"
        ),
        "/test" => array(
            "controller" => "DefaultController",
            "action" => "TestAction"
        )
    );
```
Basically, each route needs to be defined with a corresponding controller class and action method. By default this is desabled, because at the time, the router is pretty much bacis as it gets (it will not pass parameters to the controller, if you need to pass some values as arguments use $_POST method for example).

# Console
TL has a bare bones and very basic Command Line tool, to access it use:
```
$ cd path-to-project/source/core
$ sudo php console help
```
Most of the available functions will be displayed with the help command. Tiny's console can setup project files, map MySQL database from the Config.php file and generate some core MVC files along the way. It will not rewrite exsisting MVC files if there are any.

# The end, for now...
So far, that's it. 

TL MVC does have it's own Console, more configuration options and some additional helper functions like user authorization, encryption, database mapping, file generators, etc. but these are still in development (feel free to play around with them) and some of them are not done. I will update the documentation as I continue to update the code. The whole idea was to make something bare bones and simple to build upon, but with enough flexibility. I do plan on giving it multiple database support, custom routing, ORM and REST API library in the future. 
