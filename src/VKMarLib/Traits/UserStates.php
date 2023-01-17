<?php

namespace VKMarLib\Traits;

use VKMarLib\Exceptions\ResponseException;

/**
 * Работа со состояниями пользователя
 */
trait UserStates
{
    /**
     * Возвращает состояния пользователя (user state) запроса
     *
     * @link https://dev.vk.com/marusia/session-state#Персистентное%20хранение%20данных
     * @return array user states
     */
    public function getUserStates(): array
    {
        return $this->userState;
    }

    /**
     * Устанавливает значение $value по ключу $key в массив состояний пользователя
     *
     * @link https://dev.vk.com/marusia/session-state#Персистентное%20хранение%20данных
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setUserState(string $key, $value): void
    {
        $this->userState[$key] = $value;
    }

    /**
     * Удаляет состояние пользователя по ключу $key
     *
     * @link https://dev.vk.com/marusia/session-state#Персистентное%20хранение%20данных
     * @param string $key
     * @return void
     * @throws ResponseException
     */
    public function delUserState(string $key): void
    {
        if (isset($this->userState[$key])) {
            $this->userState[$key] = null;
        } else {
            throw new ResponseException("key " . $key . " not defined in userState");
        }
    }

    /**
     * Очищает состояния пользователя
     *
     * @link https://dev.vk.com/marusia/session-state#Персистентное%20хранение%20данных
     * @return void
     */
    public function clearUserState(): void
    {
        $keys = array_keys($this->getUserStates());
        for ($i = 0; $i < count($keys); $i++) {
            $this->userState[$keys[$i]] = null;
        }
    }
}
