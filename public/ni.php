<?php
use Tips\Storage\Mongo as Storage;
require_once __DIR__ . '/../config/bootstrap.php';

$request = array_merge($_GET, $_POST);

if(!isset($request['request_id']) OR !isset($request['number'])){
    return; //not an ni response
}

$service = new Storage($db);
$service->updateNumber($request['number'], $request);