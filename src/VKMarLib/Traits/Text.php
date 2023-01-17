<?php

namespace VKMarLib\Traits;

use VKMarLib\Exceptions\ValidationException;

/**
 * Работа с текстом
 */
trait Text
{
    /**
     * Устанавливает текст ответа
     *
     * @param string $text text
     * @return void
     * @throws ValidationException
     */
    public function setText(string $text): void
    {
        if (strlen($text) > 0) {
            $this->responseText = $text;
        } else {
            throw new ValidationException("Text cannot be empty");
        }
    }

    /**
     * Возвращает подготовленный текст для ответа Марусе
     *
     * @return string text
     */
    private function getText(): string
    {
        return $this->responseText;
    }
}