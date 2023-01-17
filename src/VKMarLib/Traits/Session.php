<?php

namespace VKMarLib\Traits;

/**
 * Работа с Session
 */
trait Session
{
    /**
     * Возвращает session запроса
     *
     * @return object session
     */
    private function getSession(): object
    {
        return $this->session;
    }

    /**
     * Устанавливает конец сессии
     *
     * @return void
     */
    public function setEndSession(): void
    {
        $this->endSession = true;
    }

    /**
     * Возвращает статус конца сессии
     *
     * @return bool endSession's status
     */
    private function getEndSession(): bool
    {
        return $this->endSession;
    }
}

