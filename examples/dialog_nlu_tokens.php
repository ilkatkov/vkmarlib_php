<?php

include_once __DIR__ . '../vendor/autoload.php';

use VKMarLib\Skill;

$content = file_get_contents('php://input');
$m = new Skill($content);

if ($m->existInNluTokens("привет", "здравствуйте")) {
    $m->setResponseText("Добрый день! Это пример диалога.");
} elseif ($m->existInNluTokens("автор")) {
    $m->setResponseText("Эту библиотеку написал Илья Катков.");
} elseif ($m->existInNluTokens("пока", "стоп")) {
    $m->setResponseText("Пока-пока!");
    $m->setEndSession();
} else {
    $m->setResponseText("Даже не знаю, что вам на это ответить.");
}

echo $m->getResponseJson();