<?php
chdir(__DIR__ . '/../');
require_once('config/env.php');
require_once('src/Tips/Storage/StorageInterface.php');
require_once('src/Tips/Storage/Mongo.php');

$database = substr(MONGO_DSN, strrpos(MONGO_DSN, '/' ) + 1);
$mongo = new MongoClient(MONGO_DSN);
$db = $mongo->selectDB($database);

function smart_log($data){
    // var dump any non-string
    if(!is_string($data)){
        ob_start();
        var_dump($data);
        $data = ob_get_clean();
    }

    if($json = json_decode($data, true)){
        array_walk_recursive($json, function(&$value, $key){
            switch($key){
                case "number":
                case "to":
                    $value = substr($value, 0, (strlen($value) - 4)) . '----';
                    break;
            }
        });
        $data = 'JSON DATA' . PHP_EOL . json_encode($json, JSON_PRETTY_PRINT);
    }

    error_log($data);
}