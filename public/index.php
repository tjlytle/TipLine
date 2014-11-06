<?php
use Tips\Storage\Mongo as Storage;
require_once __DIR__ . '/../config/bootstrap.php';
$service = new Storage($db);

session_start();

if(isset($_SESSION['request_id']) AND isset($_POST['code'])){ //login with code

} elseif(isset($_POST['number'])){ //got number

}

include 'template.phtml';
session_write_close();