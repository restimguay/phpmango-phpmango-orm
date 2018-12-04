<?php

require __DIR__.'/../vendor/autoload.php';
$config = array_merge(
    require __DIR__.'/../config/database.php'
);
define('DB_ENVIRONMENT','dev');
use MGO\database\Connection;
use MGO\database\DBConfig;
use MGO\model\BaseModel;


$dbConfig = new DBConfig();
$dbConfig->setConfig($config['db'],DB_ENVIRONMENT);
$conn = new Connection($dbConfig);

$conn->setConfig($dbConfig);
$conn->start();

$db = new BaseModel();
$result = $db->findIn('member')->by(['id'=>1])->fetchAssoc();
var_dump($result);
$result = $db->findIn('member')->by(['id'=>1])->fetchObject();
var_dump($result);

