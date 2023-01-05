<?php

include_once __DIR__ . '../vendor/autoload.php';

use VKMarLib\VKMarSkill;

/**
 * Устанавливаем источник запроса от Маруси
 */
$source = 'php://input';

$m = new VKMarSkill($source);

if ($m->existInNluTokens("привет", "здравствуйте")) {
    $m->setResponseText("Добрый день! Это пример диалога.");
} elseif ($m->existInNluTokens("автор")) {
    $m->setResponseText("Эту библиотеку написал Илья Катков.");
} elseif ($m->existInNluTokens("пока", "стоп")) {
    $m->setResponseText("Пока-пока!");
    $m->setEndSession();
}

echo $m->getResponseJson();