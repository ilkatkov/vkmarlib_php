<?php

namespace ilkatkov\VKMarLib;

use ilkatkov\VKMarLib\Exceptions\MarusiaRequestException;

class VKMarSkill
{
    private $version;
    private $session;
    private $meta;
    private array $nluTokens;

    /**
     * Создает объект для работы с запросами от Маруси
     * Creates an object for working with requests from Marusia
     *
     * @param string $source источник чтения запроса от Маруси / the source of the request reading from Marusia
     *
     * @throws MarusiaRequestException
     */
    public function __construct(string $source)
    {
        $jsonData = json_decode(file_get_contents($source));
        if (isset($jsonData->version)) {
            $this->version = $jsonData->version;
        } else {
            throw new MarusiaRequestException('Invalid parse \'version\' in MarusiaRequest from source: ' . $source);
        }
        if (isset($jsonData->session)) {
            $this->session = $jsonData->session;
        } else {
            throw new MarusiaRequestException('Invalid parse \'version\' in MarusiaRequest from source: ' . $source);
        }
        if (isset($jsonData->request->nlu->tokens)) {
            $this->nluTokens = $jsonData->request->nlu->tokens;
        } else {
            throw new MarusiaRequestException('Invalid parse \'request->nlu->tokens\' in MarusiaRequest from source: ' . $source);
        }
        if (isset($jsonData->meta)) {
            $this->meta = $jsonData->meta;
        } else {
            throw new MarusiaRequestException('Invalid parse \'meta\' in MarusiaRequest from source: ' . $source);
        }
    }

    /**
     * Возвращает распознаные слова в виде массива строк /
     * Returns recognized words as an array of strings
     *
     * @return array
     *
     * @throws MarusiaRequestException
     */
    public function getNluTokens(): array
    {
        if (isset($this->nluTokens)) {
            return $this->nluTokens;
        } else {
            throw new MarusiaRequestException('nluTokens is not defined');
        }
    }

    /**
     * Возвращает название города клиента на русском языке /
     * Returns the name of the client's city in Russian
     *
     * @return string
     *
     * @throws MarusiaRequestException
     */
    public function getClientCity() : string {
        if (isset($this->meta->_city_ru)) {
            return $this->meta->_city_ru;
        } else {
            throw new MarusiaRequestException('City is not defined');
        }
    }

}