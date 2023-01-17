<?php

namespace VKMarLib;

use VKMarLib\Exceptions\RequestException;
use VKMarLib\Traits\Buttons;
use VKMarLib\Traits\Cards;
use VKMarLib\Traits\Meta;
use VKMarLib\Traits\Push;
use VKMarLib\Traits\Request;
use VKMarLib\Traits\Response;
use VKMarLib\Traits\Session;
use VKMarLib\Traits\SessionStates;
use VKMarLib\Traits\Text;
use VKMarLib\Traits\Tokens;
use VKMarLib\Traits\Tts;
use VKMarLib\Traits\UserStates;
use VKMarLib\Traits\Version;

/**
 * Основной класс для работы с навыком Маруси
 */
class Skill
{
    use SessionStates;
    use UserStates;
    use Buttons;
    use Tokens;
    use Text;
    use Tts;
    use Push;
    use Cards;
    use Meta;
    use Session;
    use Request;
    use Version;
    use Response;

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
}