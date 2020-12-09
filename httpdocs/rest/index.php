<?php
require_once '../../application/bootstrap.php';
$config = \Pressmind\Registry::getInstance()->get('config');
$server = new \Pressmind\REST\Server($config['rest']['server']['api_endpoint']);
$server->handle();
