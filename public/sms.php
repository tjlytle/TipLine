<?php
use Tips\Storage\Mongo as Storage;
require_once __DIR__ . '/../config/bootstrap.php';
$service = new Storage($db);

$request = array_merge($_GET, $_POST);

if(!isset($request['to']) OR !isset($request['msisdn']) OR !isset($request['text'])){
    smart_log('not a request from nexmo');
    return;
}

try{
    smart_log('checking tip');
    $service->addTip($request['text'], $request['msisdn'], $request['to']);
    $text = 'Thanks for the tip!';
} catch (Exception $e) {
    smart_log('invalid tip, sending help');
    $text = 'Please send a tip with a comma between the context, and the advice.';
}

$url = 'https://rest.nexmo.com/sms/json?' . http_build_query([
        'api_key' => NEXMO_KEY,
        'api_secret' => NEXMO_SECRET,
        'to' => $request['msisdn'],
        'from' => $request['to'],
        'text' => $text
    ]);

smart_log('sending reply');
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
smart_log($response);