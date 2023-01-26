<?php
// пример отправки Push-уведомления без нагрузки 'payload'

include_once __DIR__ . '../vendor/autoload.php';

use VKMarLib\Skill;

$content = file_get_contents('php://input');
$m = new Skill($content);

$m->setPush("Это пуш-уведомление без нагрузки!");
$m->setText("На устройство было отправлено Push-уведомление!");
$m->setEndSession();
echo $m->getResponseJson();