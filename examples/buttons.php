<?php
// пример вывода кнопок

include_once __DIR__ . '../vendor/autoload.php';

use VKMarLib\Skill;

$content = file_get_contents('php://input');
$m = new Skill($content);

$m->setText('Выбери жанр музыки:');
$m->addButton('Рэп'); // добавление одной кнопки
$m->addButtons( // добавление нескольких кнопок
    array(
        'Рэп',
        'Хип-хоп',
        'Классика',
        'Рок',
        'Джаз'
    )
);
$m->setEndSession();
echo $m->getResponseJson();