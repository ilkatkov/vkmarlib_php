<?php

include_once __DIR__ . '../vendor/autoload.php';

use VKMarLib\Skill;

$content = file_get_contents('php://input');
$m = new Skill($content);

$m->setText("Hello, world!");
$m->setEndSession();
echo $m->getResponseJson();