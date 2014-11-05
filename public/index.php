<?php
use Tips\Storage\Mongo as Storage;
require_once __DIR__ . '/../config/bootstrap.php';
$service = new Storage($db);

session_start();

if(isset($_SESSION['request_id']) AND isset($_POST['code'])){ //login with code
    $url = 'http://api.nexmo.com/verify/check/json?' . http_build_query([
            'api_key' => NEXMO_KEY,
            'api_secret' => NEXMO_SECRET,
            'request_id' => $_SESSION['request_id'],
            'code' => $_POST['code']
        ]);

    smart_log('requesting verification check');

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    smart_log($response);

    $response = json_decode($response, true);

    if(0 == $response['status']){
        $tips = $service->getTips($_SESSION['number']);
        $_SESSION = [];
    }

} elseif(isset($_POST['number'])){ //got number
    $url = 'http://api.nexmo.com/verify/json?' . http_build_query([
            'api_key' => NEXMO_KEY,
            'api_secret' => NEXMO_SECRET,
            'number' => $_POST['number'],
            'brand' => 'tipline'
        ]);

    smart_log('requesting verification data');

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    smart_log($response);

    $response = json_decode($response, true);

    if(isset($response['request_id'])){
        $_SESSION['request_id'] = $response['request_id'];
        $_SESSION['number'] = $_POST['number'];
    }
}

include 'template.phtml';
session_write_close();