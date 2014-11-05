<?php
use Tips\Storage\Mongo as Storage;
require_once __DIR__ . '/../config/bootstrap.php';
$service = new Storage($db);
$request = array_merge($_GET, $_POST);

if(!isset($request['request_id']) OR !isset($request['number'])){
    smart_log('not an NI request');
    return; //not an ni response
}

smart_log('updating number info, status: ' . $request['status']);
$service->updateNumber($request['number'], $request);
