<?php
use Tips\Service;
require_once __DIR__ . '/../config/bootstrap.php';

// check get and post
$request = array_merge($_GET, $_POST);

// request from nexmo?
if(!isset($request['to']) OR !isset($request['text']) OR !isset($request['msisdn'])){
    error_log('not from nexmo');
    return;
}

error_log('got request from nexmo');

$service = new Service($mongo);

try{
    $service->addTip($request['text'], $request['msisdn']);
    $text = 'Thanks for the tip.';
    error_log('added tip');
} catch (Exception $e) {
    error_log('error adding tip: ' . $e->getMessage());
    $text = 'Send a tip with a comma between the context and the tip: If you want to build something with SMS, use Nexmo.';
}

$url = 'http://rest.nexmo.com/sms/json?' . http_build_query([
        'api_key'    => NEXMO_KEY,
        'api_secret' => NEXMO_SECRET,
        'to'         => $request['msisdn'],
        'from'       => $request['to'],
        'text'       => $text
    ]);

error_log('sending reply');
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
error_log($response);