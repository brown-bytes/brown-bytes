<?php
echo "Testing Environment:";
echo $_SERVER['PATH_INFO'];
error_reporting(E_ALL);


use Phalcon\Mvc\Application;
use Phalcon\Config\Adapter\Ini as ConfigIni;


try {
    //define the base path of the application
    define('BASE_PATH', dirname(__DIR__));
    define('APP_PATH', BASE_PATH . '/');

    $config = new ConfigIni(APP_PATH . 'app/config/config.ini');

    require_once(APP_PATH . 'app/config/loader.php');

    $application = new Application(new Services($config));
    
    echo $application->handle(!empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : null)->getContent();
} catch(Exception $e) {
    echo $e->getMessage()."<br />";
    echo "<pre>".$e->getTraceAsString()."</pre>";

}
/*
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\DbAdapter;
use Phalcon\Loader;
use Phalcon\Mvc\View;

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
	[
	    APP_PATH . '/controllers/',
	    APP_PATH . '/models/',
	]
);

$loader->register();

//Create a DI
$di = new FactoryDefault();

// Setup the view component
$di->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

// Setup a base URI
$di->set(
    'url',
    function () {
        $url = new UrlProvider();
        $url->setBaseUri('/');
        return $url;
    }
);

$di->set(
	"db",
	function () {
    	return new DbAdapter(
        	[
            	"host"     => "localhost",
            	"username" => "root",
            	"password" => "",
            	"dbname"   => "brown_bytes",
        	]
    	);
	}
);

$application = new Application($di);

try {
    // Handle the request
    $response = $application->handle();

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}

*/

?>