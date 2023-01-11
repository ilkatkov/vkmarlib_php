<?php

namespace VKMarLib;

use VKMarLib\Exceptions\ResponseException;
use VKMarLib\Exceptions\RequestException;
use VKMarLib\Exceptions\ValidationException;
use VKMarLib\Classes\Card;

/**
 * Основной класс для работы с навыком Маруси
 */
class Skill
{
    private string $version;
    private object $session;
    private object $meta;
    private object $request;
    private string $responseText;
    private string $responseTts;
    private array $buttons;
    private array $sessionState = [];
    private array $userState = [];
    private array $push = [];
    private array $cards = [];
    private bool $endSession = false;


    /**
     * Создает объект для работы с запросами от Маруси
     *
     * @link https://dev.vk.com/marusia/api
     * @param string $json запрос от Маруси в виде JSON
     * @throws RequestException
     */
    public function __construct(string $json)
    {
        $jsonData = json_decode($json);
        if (isset($jsonData->version)) {
            $this->version = $jsonData->version;
        } else {
            throw new RequestException('Invalid parse \'version\' from MarusiaRequest');
        }
        if (isset($jsonData->session)) {
            $this->session = $jsonData->session;
        } else {
            throw new RequestException('Invalid parse \'version\' from MarusiaRequest');
        }
        if (isset($jsonData->request)) {
            $this->request = $jsonData->request;
        } else {
            throw new RequestException('Invalid parse \'request\' from MarusiaRequest');
        }
        if (isset($jsonData->meta)) {
            $this->meta = $jsonData->meta;
        } else {
            throw new RequestException('Invalid parse \'meta\' from MarusiaRequest');
        }
        if (isset($jsonData->state->session)) {
            $this->sessionState = (array)$jsonData->state->session;
        } else {
            throw new RequestException('Invalid parse \'sessionState\' from MarusiaRequest');
        }
        if (isset($jsonData->state->user)) {
            $this->userState = (array)$jsonData->state->user;
        } else {
            throw new RequestException('Invalid parse \'userState\' from MarusiaRequest');
        }
    }

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
     * Возвращает version запроса
     *
     * @return string version
     */
    private function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Возвращает request Маруси
     *
     * @return object request
     */
    private function getRequest(): object
    {
        return $this->request;
    }

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
     * Возвращает распознанные слова в виде массива строк
     *
     * @return array nlu tokens
     * @throws RequestException
     */
    public function getNluTokens(): array
    {
        if (isset($this->getRequest()->nlu->tokens)) {
            return $this->getRequest()->nlu->tokens;
        } else {
            throw new RequestException('nluTokens is not defined');
        }
    }

    /**
     * Возвращает true, если  хотя бы одно слово из аргументов находится в nluTokens
     *
     * @param ...$values
     * @return bool result
     * @throws RequestException
     */
    public function existInNluTokens(...$values): bool
    {
        return count(array_intersect($values, $this->getNluTokens())) > 0;
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

    /**
     * Устанавливает текст ответа
     *
     * @param string $text text
     * @return void
     * @throws ValidationException
     */
    public function setResponseText(string $text): void
    {
        if (strlen($text) > 0) {
            $this->responseText = $text;
        } else {
            throw new ValidationException("ResponseText cannot be empty");
        }
    }

    /**
     * Возвращает подготовленный текст для ответа Марусе
     *
     * @return string text
     */
    private function getResponseText(): string
    {
        return $this->responseText;
    }

    /**
     * Устанавливает TTS ответа
     *
     * @link https://dev.vk.com/marusia/sound
     * @param string $tts TTS
     * @return void
     * @throws ValidationException
     */
    public function setResponseTts(string $tts): void
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
    private function getResponseTts(): string
    {
        return $this->responseTts;
    }


    /**
     * Добавляет кнопку с именем $title
     *
     * @param string $title
     * @return void
     * @throws ValidationException
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
            throw new ValidationException("Button's title cannot be empty");
        }
    }

    /**
     * Добавляет кнопки с именами из массива $titles
     *
     * @param array $titles
     * @return void
     * @throws ValidationException
     */
    public function addResponseButtons(array $titles): void
    {
        if (count($titles) > 0) {
            foreach ($titles as $title) {
                $this->addResponseButton($title);
            }
        } else {
            throw new ValidationException('Array $titles cannot be empty');
        }

    }

    /**
     * Возвращает массив кнопок для ответа Марусе
     *
     * @return array buttons
     */
    private function getResponseButtons(): array
    {
        return $this->buttons;
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
    public function setResponseSessionState(string $key, $value): void
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
    public function delResponseSessionState(string $key): void
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
    public function clearResponseSessionState(): void
    {
        $this->sessionState = [];
    }

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
    public function setResponseUserState(string $key, $value): void
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
    public function delResponseUserState(string $key): void
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
    public function clearResponseUserState(): void
    {
        $keys = array_keys($this->getUserStates());
        for ($i = 0; $i < count($keys); $i++) {
            $this->userState[$keys[$i]] = null;
        }
    }

    /**
     * Возвращает ассоциативный массив состояний сессии
     *
     * @link https://dev.vk.com/marusia/session-state#Хранение%20данных%20в%20сессии
     * @return array
     */
    private function getResponseSessionStates(): array
    {
        return $this->sessionState;
    }

    /**
     * Возвращает ассоциативный массив состояний пользователя
     *
     * @link https://dev.vk.com/marusia/session-state#Персистентное%20хранение%20данных
     * @return array
     */
    private function getResponseUserStates(): array
    {
        return $this->userState;
    }

    /**
     * Устанавливает пуш уведомление с текстом $text и нагрузкой $payload
     *
     * @link https://dev.vk.com/marusia/notifications
     * @param string $text
     * @param array $payload
     * @throws ValidationException
     * @return void
     */
    public function setPush(string $text, array $payload): void
    {
        if (strlen($text) > 0) {
            $this->push["push_text"] = $text;
        } else {
            throw new ValidationException("Text for Push cannot be empty");
        }

        if (count(array_keys($payload)) == 1) {
            $this->push["payload"] = $payload;
        } else {
            throw new ValidationException("Length of payload for Push should be equal 1");
        }
    }

    /**
     * Возвращает установленное пуш уведомление
     *
     * @link https://dev.vk.com/marusia/notifications
     * @return array
     */
    private function getPush(): array
    {
        return $this->push;
    }

    /**
     * Возвращает карточки ответа
     *
     * @link https://dev.vk.com/marusia/cards
     * @return array
     */
    private function getCards() : array
    {
        return $this->cards;
    }

    /**
     * Добавляет заполненную карточку в ответ Маруси
     *
     * @link https://dev.vk.com/marusia/cards
     * @param Card $card
     * @return void
     * @throws ValidationException
     */
    public function addCard(Card $card) : void
    {
        $this->cards[] = $card->getCard();
    }

    /**
     * Формирует и возвращает JSON ответ для Маруси
     *
     * @link https://dev.vk.com/marusia/api#Структура%20ответа
     * @return string
     */
    public function getResponseJson(): string
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

        if (count(array_keys($this->push)) > 0) {
            $response["response"]["push"] = $this->getPush();
        }

        if (isset($this->buttons)) {
            $response["response"]["buttons"] = $this->getResponseButtons();
        }

        if (count($this->cards) == 1) {
            $response["response"]["card"] = $this->getCards()[0];
        } elseif (count($this->cards) > 1) {
            $response["response"]["commands"] = $this->getCards();
        }

        $response["session_state"] = $this->getResponseSessionStates();
        $response["user_state_update"] = $this->getResponseUserStates();

        return json_encode($response);
    }
}