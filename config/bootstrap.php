<?php
chdir(__DIR__ . '/../');
require_once('config/env.php');
require_once('src/Tips/Service.php');

$mongo = new Mongo(MONGO_DSN);