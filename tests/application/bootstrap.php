<?php

error_reporting(E_ALL | E_STRICT);

defined("APPLICATION_PATH")
    || define("APPLICATION_PATH", realpath(dirname(__FILE__) . "/../../application"));
defined("APPLICATION_ENV")
    || define("APPLICATION_ENV", (getenv("APPLICATION_ENV") ? getenv("APPLICATION_ENV") : "testing"));
    
set_include_path(implode(PATH_SEPARATOR, array(
    get_include_path(),
    realpath(APPLICATION_PATH . "/../library"),
)));

require_once 'Zend/Loader/Autoloader.php';
require_once 'Zend/Application/Module/Autoloader.php';

/**
 * @var Zend_Application_Module_Autoloader
 */
$autoloader = new Zend_Application_Module_Autoloader(array(
    "namespace" => "SimpleCal",
    "basePath" => APPLICATION_PATH
));
