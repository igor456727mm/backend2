<?php
//header('Content-Type: application/json');
error_reporting(E_ALL);
//error_reporting(0);
ini_set('display_errors', 1);
ini_set('max_execution_time', 900);
ini_set('sendmail_from','info@dostavkalm.ru');
$root = dirname(__DIR__);
$loader = require $root . '/vendor/autoload.php';
$loader->add('', $root.'/classes/');
$pixie = new \App\Pixie();
//header('Content-Type: application/json');
$pixie->bootstrap($root)->handle_http_request();
/*
if (defined('API')) {
    header('Content-Type: application/json');
}*/
?>
