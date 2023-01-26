<?php
// пример TTS

include_once __DIR__ . '../vendor/autoload.php';

use VKMarLib\Skill;

$content = file_get_contents('php://input');
$m = new Skill($content);

$m->setText("Поздравляю! Вы правильно ответили на все мои вопросы!");
$m->setTts("Поздравляю! <speaker audio=marusia-sounds/game-win-1> Вы правильно ответили на все мои вопросы!");
$m->setEndSession();
echo $m->getResponseJson();