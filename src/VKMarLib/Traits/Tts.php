<?php

namespace VKMarLib\Traits;

use VKMarLib\Exceptions\ValidationException;

/**
 * Работа с TTS
 */
trait Tts
{
    /**
     * Устанавливает TTS ответа
     *
     * @link https://dev.vk.com/marusia/sound
     * @param string $tts TTS
     * @return void
     * @throws ValidationException
     */
    public function setTts(string $tts): void
    {
        if (strlen($tts) > 0) {
            $this->responseTts = $tts;
        } else {
            throw new ValidationException("ResponseTts cannot be empty");
        }
    }

    /**
     * Возвращает подготовленный TTS для ответа Марусе
     *
     * @return string TTS
     */
    private function getTts(): string
    {
        return $this->responseTts;
    }
}
