<?php
//phpinfo();


ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);

use Phalcon\Mvc\Application;
use Phalcon\Config\Adapter\Ini as ConfigIni;

try {
    define('APP_PATH', realpath('..') . '/');
    //\Phalcon\Mvc\Model::setup(['notNullValidations' => false]);

    /**
     * Read the configuration
     */
    $config = new ConfigIni(APP_PATH . 'app/config/config.ini');
    if (is_readable(APP_PATH . 'app/config/config.ini.dev')) {
        $override = new ConfigIni(APP_PATH . 'app/config/config.ini.dev');
        $config->merge($override);
    }
    /**
     * Auto-loader configuration
     */
    require APP_PATH . 'app/library/base/services.php';
    require APP_PATH . 'app/config/loader.php';
    //$services = new Services($config);
    $application = new Application(new Services($config));
    //echo 'app<br/>';
    //var_dump($application);
    //$response = $services->get('response');
    //var_dump($response);
    //echo $application->handle('/')->getContent();
    //try {
	//	throw new \Exception('foo');
    //} catch (\Exception $e) {
//	$application = new Application($services);
    echo $application->handle(!empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] :null)->getContent();
//	$services->remove('response');
  //  }

    // NGINX - PHP-FPM already set PATH_INFO variable to handle route
    //echo $application->handle(!empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : null)->getContent();
} catch (Exception $e){
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}



