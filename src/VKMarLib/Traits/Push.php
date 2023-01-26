<?php

namespace VKMarLib\Traits;

use VKMarLib\Exceptions\ValidationException;

/**
 * Работа с Push уведомлениями
 */
trait Push
{
    /**
     * Устанавливает пуш уведомление с текстом $text и нагрузкой $payload
     *
     * @link https://dev.vk.com/marusia/notifications
     * @param string $text
     * @param array $payload
     * @throws ValidationException
     * @return void
     */
    public function setPush(string $text, array $payload = []): void
    {
        if (strlen($text) > 0) {
            $this->push["push_text"] = $text;
        } else {
            throw new ValidationException("Text for Push cannot be empty");
        }

        $payloadLength = count(array_keys($payload));
        if ($payloadLength == 1) {
            $this->push["payload"] = $payload;
        } elseif ($payloadLength > 1) {
            throw new ValidationException("Length of payload for Push should be equal 1");
        }
    }

    /**
     * Возвращает установленное пуш уведомление
     *
     * @link https://dev.vk.com/marusia/notifications
     * @return array
     */
    private function getPush(): array
    {
        return $this->push;
    }
}