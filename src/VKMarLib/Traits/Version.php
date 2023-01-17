<?php

namespace VKMarLib\Traits;

/**
 * Работа с Version
 */
trait Version
{
    /**
     * Возвращает version запроса
     *
     * @return string version
     */
    private function getVersion(): string
    {
        return $this->version;
    }
}