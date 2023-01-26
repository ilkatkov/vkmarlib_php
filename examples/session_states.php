<?php
// пример работы со состояниями сессии

include_once __DIR__ . '../vendor/autoload.php';

use VKMarLib\Skill;

$content = file_get_contents('php://input');
$m = new Skill($content);

if ($m->getSessionState("start")) {
    $company = $m->getSessionState("company");
    $project = $m->getSessionState("project");
    $text = "В компании {$company} есть проект \"{$project}\"";
    $m->setText($text);
    $m->setEndSession();
} else {
    $m->setText("Загружаю в сессию необходимые данные.");
    $m->setSessionState("start", true);
    $m->setSessionState("company", "VK");
    $m->setSessionState("project", "Маруся");
}

echo $m->getResponseJson();