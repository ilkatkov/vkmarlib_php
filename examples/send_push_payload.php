<?php
// пример отправки Push-уведомления с нагрузкой 'payload'

include_once __DIR__ . '../vendor/autoload.php';

use VKMarLib\Skill;

$content = file_get_contents('php://input');
$m = new Skill($content);

$m->setPush("Это пуш-уведомление!",
    array("author" => "vk.com/ilkatkov")
);
$m->setText("На устройство было отправлено Push-уведомление!");
$m->setEndSession();
echo $m->getResponseJson();