<?php
use Tips\Storage\Mongo as Storage;
require_once __DIR__ . '/../config/bootstrap.php';

$service = new Storage($db);
$numbers = $service->getNumbers();

echo "---TipLine Stats---" . PHP_EOL;
foreach($numbers as $number){
    echo $number['masked'] . ': ';
    if(isset($number['info'])){
        $info = $number['info'];
        echo implode(' | ', [$info['number_type'], $info['ported'], $info['carrier_country_code'], $info['carrier_network_name']]);
    }
}

echo PHP_EOL;