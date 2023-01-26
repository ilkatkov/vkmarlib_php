<?php
// пример работы со состояниями пользователя

include_once __DIR__ . '../vendor/autoload.php';

use VKMarLib\Skill;

$content = file_get_contents('php://input');
$m = new Skill($content);

if ($m->existInTokens("сброс", "заново")) {
    $m->setText("Данные пользователя очищены!");
    $m->clearUserStates();
} elseif ($m->getUserState("name") !== null) {
    $name = $m->getUserState("name");
    $text = "Привет, {$name}, как дела?";
    $m->setText($text);
} else {
    $m->setText("Назови свое имя:");
    $m->setUserState("name", $m->getTokens()[0]);
}

echo $m->getResponseJson();