<?php

namespace VKMarLib\Traits;

/**
 * Работа с ответами для Маруси
 */
trait Response {
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
            $response["response"]["text"] = $this->getText();
        }

        if (isset($this->responseTts)) {
            $response["response"]["tts"] = $this->getTts();
        }

        if (count(array_keys($this->push)) > 0) {
            $response["response"]["push"] = $this->getPush();
        }

        if (isset($this->buttons)) {
            $response["response"]["buttons"] = $this->getButtons();
        }

        if (count($this->cards) == 1) {
            $response["response"]["card"] = $this->getCards()[0];
        } elseif (count($this->cards) > 1) {
            $response["response"]["commands"] = $this->getCards();
        }

        $response["session_state"] = $this->getSessionStates();
        $response["user_state_update"] = $this->getUserStates();

        return json_encode($response);
    }
}