<?php
// пример построения диалога с пользователем

include_once __DIR__ . '../vendor/autoload.php';

use VKMarLib\Skill;

$content = file_get_contents('php://input');
$m = new Skill($content);

if ($m->existInTokens("привет", "здравствуйте")) {
    $m->setText("Добрый день! Это пример диалога.");
} elseif ($m->existInTokens("автор")) {
    $m->setText("Эту библиотеку написал Илья Катков.");
} elseif ($m->existInTokens("пока", "стоп")) {
    $m->setText("Пока-пока!");
    $m->setEndSession();
} else {
    $m->setText("Даже не знаю, что вам на это ответить.");
}

echo $m->getResponseJson();