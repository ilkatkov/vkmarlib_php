<?php

include_once __DIR__ . '../vendor/autoload.php';

use VKMarLib\VKMarSkill;

$m = new VKMarSkill('php://input');
$m->setResponseText("Hello, world!");
$m->setEndSession();
echo $m->getResponseJson();