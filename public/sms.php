<?php
use Tips\Storage\Mongo as Storage;
require_once __DIR__ . '/../config/bootstrap.php';

$request = array_merge($_GET, $_POST);

if(!isset($request['to']) OR !isset($request['msisdn']) OR !isset($request['text'])){
    error_log('not an inbound message');
    return;
}

error_log('got inbound message');

$service = new Storage($db);

try{
    $service->addTip($request['text'], $request['msisdn']);
    $text = 'Thanks for the tip!';
} catch (Exception $e) {
    error_log('got a bad tip: ' . $e->getMessage());
    $text = 'Send a tip with a comma between the context and the tip: If you want to build an SMS app, use Nexmo!';
}

$url = 'http://rest.nexmo.com/sms/json?' . http_build_query([
        'api_key' => NEXMO_KEY,
        'api_secret' => NEXMO_SECRET,
        'to' => $request['msisdn'],
        'from' => $request['to'],
        'text' => $text
    ]);

error_log('sending the reply');

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

error_log($response);

