<?php
chdir(__DIR__ . '/../');
require_once('config/env.php');
require_once('src/Tips/Storage/StorageInterface.php');
require_once('src/Tips/Storage/Mongo.php');

$database = substr(MONGO_DSN, strrpos(MONGO_DSN, '/' ) + 1);
$mongo = new MongoClient(MONGO_DSN);
$db = $mongo->selectDB($database);