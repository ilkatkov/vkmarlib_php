<?php

namespace VKMarLib\Traits;

use VKMarLib\Classes\Card;
use VKMarLib\Exceptions\ValidationException;

/**
 * Работа с карточками
 */
trait Cards
{
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
     * Возвращает карточки ответа
     *
     * @link https://dev.vk.com/marusia/cards
     * @return array
     */
    private function getCards() : array
    {
        return $this->cards;
    }
}
