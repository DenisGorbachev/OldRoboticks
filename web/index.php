<?php

if (file_exists(dirname(__FILE__).'/../cache/debug')) {
    $env = 'dev';
    $debug = true;
} else {
    $env = 'prod';
    $debug = false;
}

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', $env, $debug);
sfContext::createInstance($configuration)->dispatch();
