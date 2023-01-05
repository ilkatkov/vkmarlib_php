<?php

include_once __DIR__ . '../vendor/autoload.php';

use VKMarLib\VKMarSkill;

/**
 * Устанавливаем источник запроса от Маруси
 */
$source = 'php://input';

$m = new VKMarSkill($source);
$m->setResponseText("Hello, world!");
$m->setEndSession();
echo $m->getResponseJson();