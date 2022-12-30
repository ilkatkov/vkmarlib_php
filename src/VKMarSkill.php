<?php

namespace VKMarLib;

use VKMarLib\Exceptions\MaruisaResponseException;
use VKMarLib\Exceptions\MarusiaRequestException;
use VKMarLib\Exceptions\MarusiaValidationException;

require_once "Exceptions/MarusiaRequestException.php";
require_once "Exceptions/MarusiaResponseException.php";
require_once "Exceptions/MarusiaValidationException.php";

class VKMarSkill
{
    private $version;
    private $session;
    private $meta;
    private $nluTokens;
    private $response;
    private $responseText;
    private $responseTts;

    /**
     * Создает объект для работы с запросами от Маруси /
     * Creates an object for working with requests from Marusia
     *
     * @link https://dev.vk.com/marusia/api
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
     * Возвращает session запроса /
     * Returns session form request
     *
     * @return object session
     */
    private function getSession(): object
    {
        return $this->session;
    }

    /**
     * Возвращает version запроса /
     * Returns version form request
     *
     * @return string version
     */
    private function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Возвращает распознаные слова в виде массива строк /
     * Returns recognized words as an array of strings
     *
     * @return array nlu tokens
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
     * @return string city
     *
     * @throws MarusiaRequestException
     */
    public function getClientCity(): string
    {
        if (isset($this->meta->_city_ru)) {
            return $this->meta->_city_ru;
        } else {
            throw new MarusiaRequestException('City is not defined');
        }
    }

    /**
     * Возвращает языковой стандарт клиента /
     * Returns the client's language standard
     *
     * @return string locale
     *
     * @throws MarusiaRequestException
     */
    public function getClientLocale(): string
    {
        if (isset($this->meta->locale)) {
            return $this->meta->locale;
        } else {
            throw new MarusiaRequestException('Locale is not defined');
        }
    }

    /**
     * Возвращает часовой пояс клиента /
     * Returns the client's time zone
     *
     * @return string timezone
     *
     * @throws MarusiaRequestException
     */
    public function getClientTimezone(): string
    {
        if (isset($this->meta->timezone)) {
            return $this->meta->timezone;
        } else {
            throw new MarusiaRequestException('Timezone is not defined');
        }
    }

    /**
     * Устанавливает текст ответа /
     * Sets the response text
     *
     * @param string $text text
     * @return void
     *
     * @throws MarusiaValidationException
     */
    public function setResponseText(string $text): void
    {
        if (strlen($text) > 0) {
            $this->responseText = $text;
        } else {
            throw new MarusiaValidationException("ResponseText cannot be empty");
        }
    }

    /**
     * Возвращает подготовленный текст для ответа Марусе /
     * Returns the prepared text for the Marusia response
     *
     * @return string text
     *
     * @throws MaruisaResponseException
     */
    public function getResponseText(): string
    {
        if (isset($this->responseText)) {
            return $this->responseText;
        } else {
            throw new MaruisaResponseException("ResponseText is not defined");
        }
    }

    /**
     * Устанавливает TTS ответа /
     * Sets the response TTS
     *
     * @link https://dev.vk.com/marusia/sound
     *
     * @param string $tts TTS
     * @return void
     *
     * @throws MarusiaValidationException
     */
    public function setResponseTts(string $tts)
    {
        if (strlen($tts) > 0) {
            $this->responseTts = $tts;
        } else {
            throw new MarusiaValidationException("ResponseTts cannot be empty");
        }
    }

    /**
     * Возвращает подготовленный TTS для ответа Марусе /
     * Returns the prepared TTS for the Marusia response
     *
     * @return string TTS
     *
     * @throws MaruisaResponseException
     */
    public function getResponseTts(): string
    {
        if (isset($this->responseTts)) {
            return $this->responseTts;
        } else {
            throw new MaruisaResponseException("ResponseTts is not defined");
        }
    }

    public function getJsonResponse($end_session = false)
    {
        $this->response = array(
            "session" => $this->getSession(),
            "version" => $this->getVersion(),
            "response" => array(
                "end_session" => $end_session
            ),
        );

        if (isset($this->responseText)) {
            $this->response["response"]["text"] = $this->getResponseText();
        }

        if (isset($this->responseTts)) {
            $this->response["response"]["tts"] = $this->getResponseTts();
        }

        return json_encode($this->response);
    }

}