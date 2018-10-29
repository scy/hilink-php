<?php

require_once __DIR__ . '/vendor/autoload.php';

$c = new \scy\HiLink\Client();
$c->login();
echo $c->getStatus() . "\n";
