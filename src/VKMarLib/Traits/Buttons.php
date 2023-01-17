<?php

namespace VKMarLib\Traits;

use VKMarLib\Exceptions\ValidationException;

/**
 * Работа с кнопками
 */
trait Buttons
{
    /**
     * Добавляет кнопку с именем $title
     *
     * @param string $title
     * @return void
     * @throws ValidationException
     */
    public function addButton(string $title): void
    {
        if (strlen($title) > 0) {
            $button = array("title" => $title);

            if (!isset($this->buttons)) {
                $this->buttons = array($button);
            } else {
                $this->buttons[] = $button;
            }
        } else {
            throw new ValidationException("Button's title cannot be empty");
        }
    }

    /**
     * Добавляет кнопки с именами из массива $titles
     *
     * @param array $titles
     * @return void
     * @throws ValidationException
     */
    public function addButtons(array $titles): void
    {
        if (count($titles) > 0) {
            foreach ($titles as $title) {
                $this->addButton($title);
            }
        } else {
            throw new ValidationException('Array $titles cannot be empty');
        }

    }

    /**
     * Возвращает массив кнопок для ответа Марусе
     *
     * @return array buttons
     */
    private function getButtons(): array
    {
        return $this->buttons;
    }
}