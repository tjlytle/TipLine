<?php
use Tips\Service;
require_once __DIR__ . '/../config/bootstrap.php';

$service = new Service($mongo);

$numbers = $service->getNumbers();
error_log('found ' . count($numbers) . ' numbers');

$tips = $service->getRandom(count($numbers));

foreach($numbers as $number){
    $url = 'http://rest.nexmo.com/tts/json?' . http_build_query([
            'api_key'    => NEXMO_KEY,
            'api_secret' => NEXMO_SECRET,
            'to'         => $number,
            'from'       => NEXMO_FROM,
            'text'       => array_pop($tips)
        ]);

    error_log('sending totd to: ' . substr($number, 0, 7) . 'XXXX' . ' - ' . $text);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    error_log($response);
}