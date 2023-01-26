<?php
// пример работы с карточками

include_once __DIR__ . '../vendor/autoload.php';

use VKMarLib\Skill;
use VKMarLib\Classes\Card;

$content = file_get_contents('php://input');
$m = new Skill($content);

$m->setText("Показываю карточки:");

// Карточка со ссылкой
$linkCard = new Card("Link");
$linkCard->setImageId(777);
$linkCard->setText("Крутой текст");
$linkCard->setTitle("Мега-заголовок");
$linkCard->setUrl("https://vk.com/ilkatkov");
$m->addCard($linkCard);

// карточка с изображением
$bigImageCard = new Card("BigImage");
$bigImageCard->setImageId(123);
$m->addCard($bigImageCard);

// карточка с VK Mini Apps
$miniAppCard = new Card("MiniApp");
$miniAppCard->setUrl("https://vk.com/services?w=app7539087_142446929");
$m->addCard($miniAppCard);

$m->setEndSession();
echo $m->getResponseJson();