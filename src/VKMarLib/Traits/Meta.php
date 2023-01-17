<?php

namespace VKMarLib\Traits;

use VKMarLib\Exceptions\RequestException;

/**
 * Работа с Meta
 */
trait Meta
{
    /**
     * Возвращает meta запроса Маруси
     *
     * @return object meta
     */
    private function getMeta(): object
    {
        return $this->meta;
    }


    /**
     * Возвращает название города клиента на русском языке
     *
     * @return string city
     * @throws RequestException
     */
    public function getClientCity(): string
    {
        if (isset($this->getMeta()->_city_ru)) {
            return $this->getMeta()->_city_ru;
        } else {
            throw new RequestException('City is not defined');
        }
    }

    /**
     * Возвращает языковой стандарт клиента
     *
     * @return string locale
     * @throws RequestException
     */
    public function getClientLocale(): string
    {
        if (isset($this->getMeta()->locale)) {
            return $this->getMeta()->locale;
        } else {
            throw new RequestException('Locale is not defined');
        }
    }

    /**
     * Возвращает часовой пояс клиента
     *
     * @return string timezone
     * @throws RequestException
     */
    public function getClientTimezone(): string
    {
        if (isset($this->getMeta()->timezone)) {
            return $this->getMeta()->timezone;
        } else {
            throw new RequestException('Timezone is not defined');
        }
    }
}