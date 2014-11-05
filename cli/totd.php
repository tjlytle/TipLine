<?php
use Tips\Storage\Mongo as Storage;
require_once __DIR__ . '/../config/bootstrap.php';
$service = new Storage($db);

$numbers = $service->getNumbers();
$tips = $service->getRandom(count($numbers));

foreach($numbers as $number){
    $tip = array_pop($tips);

    smart_log('sending tip to: ' . $number['masked']);
    smart_log('tip is: ' . $tip);

    $url = 'https://rest.nexmo.com/sms/json?' . http_build_query([
            'api_key' => NEXMO_KEY,
            'api_secret' => NEXMO_SECRET,
            'to' => $number['_id'],
            'from' => $number['to'],
            'text' => $tip
        ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    smart_log($response);
}