<?php
use Tips\Storage\Mongo as Storage;
require_once __DIR__ . '/../config/bootstrap.php';

$service = new Storage($db);

$numbers = $service->getNumbers();

foreach($numbers as $number){
    $url = 'http://rest.nexmo.com/ni/json?' . http_build_query([
            'api_key' => NEXMO_KEY,
            'api_secret' => NEXMO_SECRET,
            'number' => $number['_id'],
            'callback' => 'http://live.demo.nexmo.ninja/ni.php'
        ]);

    error_log('requesting number data');

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    error_log($response);
}