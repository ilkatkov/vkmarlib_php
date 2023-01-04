<?php

use VKMarLib\VKMarSkill;

$m = new VKMarSkill('php://input');
$m->setResponseText("Hello, world!");
$m->setEndSession();
echo $m->getResponseJson();