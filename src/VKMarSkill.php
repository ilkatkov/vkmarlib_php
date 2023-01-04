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
    private string $version;
    private object $session;
    private object $meta;
    private object $request;
    private string $responseText;
    private string $responseTts;
    private array $buttons;
    private array $sessionState = [];
    private bool $endSession = false;

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
        if (isset($jsonData->request)) {
            $this->request = $jsonData->request;
        } else {
            throw new MarusiaRequestException('Invalid parse \'request\' in MarusiaRequest from source: ' . $source);
        }
        if (isset($jsonData->meta)) {
            $this->meta = $jsonData->meta;
        } else {
            throw new MarusiaRequestException('Invalid parse \'meta\' in MarusiaRequest from source: ' . $source);
        }
        if (isset($jsonData->state->session)) {
            $this->sessionState = (array)$jsonData->state->session;
        } else {
            throw new MarusiaRequestException('Invalid parse \'sessionState\' in MarusiaRequest from source: ' . $source);
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
     * Возвращает request Маруси /
     * Returns request from Marusia
     *
     * @return object request
     */
    private function getRequest(): object
    {
        return $this->request;
    }

    /**
     * Возвращает meta запроса Маруси /
     * Returns meta from Marusia's request
     *
     * @return object meta
     */
    private function getMeta(): object
    {
        return $this->meta;
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
        if (isset($this->getRequest()->nlu->tokens)) {
            return $this->getRequest()->nlu->tokens;
        } else {
            throw new MarusiaRequestException('nluTokens is not defined');
        }
    }

    /**
     * Возвращает true, если  хотя бы одно слово из аргументов находится в nluTokens /
     * Returns true if at least one word of the arguments is in nluTokens
     *
     * @param ...$values
     * @return bool result
     *
     * @throws MarusiaRequestException
     */
    public function existInNluTokens(...$values): bool
    {
        return count(array_intersect($values, $this->getNluTokens())) > 0;
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
        if (isset($this->getMeta()->_city_ru)) {
            return $this->getMeta()->_city_ru;
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
        if (isset($this->getMeta()->locale)) {
            return $this->getMeta()->locale;
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
        if (isset($this->getMeta()->timezone)) {
            return $this->getMeta()->timezone;
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
     */
    private function getResponseText(): string
    {
        return $this->responseText;
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
    public function setResponseTts(string $tts): void
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
     */
    private function getResponseTts(): string
    {
        return $this->responseTts;
    }


    /**
     * Добавляет кнопку с именем $title /
     * Adds a button named $title
     *
     * @param string $title
     * @return void
     *
     * @throws MarusiaValidationException
     */
    public function addResponseButton(string $title): void
    {
        if (strlen($title) > 0) {
            $button = array("title" => $title);

            if (!isset($this->buttons)) {
                $this->buttons = array($button);
            } else {
                $this->buttons[] = $button;
            }
        } else {
            throw new MarusiaValidationException("Button's title cannot be empty");
        }
    }

    /**
     * Добавляет кнопки с именами из массива $titles /
     * Adds buttons with names from the $titles array
     *
     * @param array $titles
     * @return void
     *
     * @throws MarusiaValidationException
     */
    public function addResponseButtons(array $titles): void
    {
        if (count($titles) > 0) {
            foreach ($titles as $title) {
                $this->addResponseButton($title);
            }
        } else {
            throw new MarusiaValidationException('Array $titles cannot be empty');
        }

    }

    /**
     * Возвращает массив кнопок для ответа Марусе /
     * Returns an array of buttons to respond to Marusia
     *
     * @return array buttons
     */
    private function getResponseButtons(): array
    {
        return $this->buttons;
    }

    /**
     * Устанавливает конец сессии /
     * Sets the end of the session
     *
     * @return void
     */
    public function setEndSession(): void
    {
        $this->endSession = true;
    }

    /**
     * Возвращает статус конца сессии /
     * Returns the end of session status
     *
     * @return bool endSession's status
     */
    private function getEndSession(): bool
    {
        return $this->endSession;
    }


    /**
     * Возвращает состояния сессии (session state) запроса /
     * Returns the session states of request
     *
     * @return array session states
     */
    public function getSessionStates(): array
    {
        return $this->sessionState;
    }

    /**
     * Устанавливает значение $value по ключу $key в массив состояний сессии /
     * Sets $value by $key to the array of session states
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setResponseSessionState(string $key, $value): void
    {
        $this->sessionState[$key] = $value;
    }

    /**
     * Удаляет состояние сессии по ключу $key /
     * Deletes the session state by $key
     *
     * @param string $key
     * @return void
     * @throws MaruisaResponseException
     */
    public function delResponseSessionState(string $key): void
    {
        if (isset($this->sessionState[$key])) {
            unset($this->sessionState[$key]);
        } else {
            throw new MaruisaResponseException("key " . $key . " not defined in sessionState");
        }
    }

    /**
     * Очищает состояния сессии /
     * Clears session states
     *
     * @return void
     */
    public function clearResponseSessionState(): void
    {
        $this->sessionState = [];
    }

    /**
     * Возвращает ассоциативный массив состояний сессии /
     * Returns an associative array of session states
     *
     * @return array
     */
    private function getResponseSessionStates(): array
    {
        return $this->sessionState;
    }

    public function getJsonResponse(): string
    {
        $response = array(
            "session" => $this->getSession(),
            "version" => $this->getVersion(),
            "response" => array(
                "end_session" => $this->getEndSession()
            ),
        );

        if (isset($this->responseText)) {
            $response["response"]["text"] = $this->getResponseText();
        }

        if (isset($this->responseTts)) {
            $response["response"]["tts"] = $this->getResponseTts();
        }

        if (isset($this->buttons)) {
            $response["response"]["buttons"] = $this->getResponseButtons();
        }

        $response["session_state"] = $this->getResponseSessionStates();

        return json_encode($response);
    }

}