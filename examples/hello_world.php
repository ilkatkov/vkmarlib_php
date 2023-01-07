<?php

include_once __DIR__ . '../vendor/autoload.php';

use VKMarLib\VKMarSkill;

$content = file_get_contents('php://input');
$m = new VKMarSkill($content);

$m->setResponseText("Hello, world!");
$m->setEndSession();
echo $m->getResponseJson();