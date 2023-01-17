<?php

namespace VKMarLib\Traits;

use VKMarLib\Exceptions\ResponseException;

/**
 * Работа со состояниями сессии
 */
trait SessionStates
{
    /**
     * Возвращает состояния сессии (session state) запроса
     *
     * @link https://dev.vk.com/marusia/session-state#Хранение%20данных%20в%20сессии
     * @return array session states
     */
    public function getSessionStates(): array
    {
        return $this->sessionState;
    }

    /**
     * Устанавливает значение $value по ключу $key в массив состояний сессии
     *
     * @link https://dev.vk.com/marusia/session-state#Хранение%20данных%20в%20сессии
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setSessionState(string $key, $value): void
    {
        $this->sessionState[$key] = $value;
    }

    /**
     * Удаляет состояние сессии по ключу $key
     *
     * @link https://dev.vk.com/marusia/session-state#Хранение%20данных%20в%20сессии
     * @param string $key
     * @return void
     * @throws ResponseException
     */
    public function delSessionState(string $key): void
    {
        if (isset($this->sessionState[$key])) {
            unset($this->sessionState[$key]);
        } else {
            throw new ResponseException("key " . $key . " not defined in sessionState");
        }
    }

    /**
     * Очищает состояния сессии
     *
     * @link https://dev.vk.com/marusia/session-state#Хранение%20данных%20в%20сессии
     * @return void
     */
    public function clearSessionState(): void
    {
        $this->sessionState = [];
    }
}