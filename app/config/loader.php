<?php

$loader = new \Phalcon\loader();
/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs([
    APP_PATH . $config->application->controllersDir,
    APP_PATH . $config->application->pluginsDir,
    APP_PATH . $config->application->libraryDir,
    APP_PATH . $config->application->modelsDir,
    APP_PATH . $config->application->formsDir,
    APP_PATH . $config->application->configDir
]);

$loader->registerClasses([
    'Services' => APP_PATH . 'app/services.php',
    'Validators' => APP_PATH . 'app/Validators.php',
    'Elements' => APP_PATH . 'app/elements.php',
    'Market' => APP_PATH . 'app/market.php'
]);
$loader->register();


