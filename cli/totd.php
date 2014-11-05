<?php
use Tips\Storage\Mongo as Storage;
require_once __DIR__ . '/../config/bootstrap.php';

$service = new Storage($db);

$numbers = $service->getNumbers();

$tips = $service->getRandom(count($numbers));

foreach($numbers as $number){
    $text = array_pop($tips);

    $url = 'http://rest.nexmo.com/tts/json?' . http_build_query([
            'api_key' => NEXMO_KEY,
            'api_secret' => NEXMO_SECRET,
            'to' => $number,
            'from' => NEXMO_FROM,
            'text' => $text
        ]);

    error_log('sending the tip: ' . $text);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //$response = curl_exec($ch);
    curl_close($ch);

    error_log($response);
}