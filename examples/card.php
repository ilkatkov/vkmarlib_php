<?php
// пример работы с одной карточкой

include_once __DIR__ . '../vendor/autoload.php';

use VKMarLib\Skill;
use VKMarLib\Classes\Card;

$content = file_get_contents('php://input');
$m = new Skill($content);

$m->setText("Показываю карточку:");

$bigImageCard = new Card("BigImage");
$bigImageCard->setImageId(456);
$m->addCard($bigImageCard);

$m->setEndSession();
echo $m->getResponseJson();