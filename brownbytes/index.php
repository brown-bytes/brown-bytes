<?php

echo "Testing Environment<br/>";
//echo APP_PATH.APP_NAME;
phpinfo();
die('Awesome');
//require_once('/app/app/config/loader.php');



error_reporting('E_ALL');

use Phalcon\Mvc\Application;
use Phalcon\Config\Adapter\Ini as ConfigIni;

try {
    define('APP_PATH', realpath('..') . '/');
    //echo APP_PATH."<br/>";
    //\Phalcon\Mvc\Model::setup(['notNullValidations' => false]);

    /**
     * Read the configuration
     */
    $config = new ConfigIni(APP_PATH . 'app/config/config.ini');
    //var_dump($config);
    if (is_readable(APP_PATH . 'app/config/config.ini.dev')) {
        $override = new ConfigIni(APP_PATH . 'app/config/config.ini.dev');
        $config->merge($override);
    }

    /**
     * Auto-loader configuration
     */
    //echo APP_PATH . 'app/config/loader.php<br/>';
    try {
        include(APP_PATH . 'app/config/loader.php');
    } catch(Exception $e) {
        echo $e;
    }
    //echo 'Past loading';
    $services = new Services($config);
    //var_dump($services);
    $application = new Application($services);

    //var_dump($application);
    //echo 'going to handle';
    // NGINX - PHP-FPM already set PATH_INFO variable to handle route
    echo $application->handle(!empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : null)->getContent();
} catch (Exception $e){
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}


?>
