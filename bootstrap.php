<?php

//root definer: application root path
define("root",str_replace("\\","/",realpath(__DIR__.'/')));

/**
 * resta system composer vendor autoload.
 * For libraries that specify autoload information, Composer generates a vendor/autoload.php file.
 * You can simply include this file and start using the classes that those libraries provide without any extra work
 * system main skeleton
 * return autoload file
 */
require_once root.''.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$app=new Resta\Foundation\Application(false);

echo 'asa';