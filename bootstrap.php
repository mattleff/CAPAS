<?php

$config = require_once __DIR__ . '/config.php';

$loader = require __DIR__.'/vendor/autoload.php';
$loader->add('MattLeff', __DIR__ . '/src/');

if($config["debug"]) {
	$loader->add('MattLeff\Tests', __DIR__ . '/tests/');
}
