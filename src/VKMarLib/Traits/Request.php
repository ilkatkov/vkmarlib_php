<?php

namespace VKMarLib\Traits;

/**
 * Работа с Request
 */
trait Request
{
    /**
     * Возвращает request Маруси
     *
     * @return object request
     */
    private function getRequest(): object
    {
        return $this->request;
    }
}
