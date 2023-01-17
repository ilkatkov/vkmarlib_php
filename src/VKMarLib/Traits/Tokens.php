<?php

namespace VKMarLib\Traits;

use VKMarLib\Exceptions\RequestException;

/**
 * Работа с распознанным вводом пользователя
 */
trait Tokens
{
    /**
     * Возвращает распознанные слова в виде массива строк
     *
     * @return array nlu tokens
     * @throws RequestException
     */
    public function getTokens(): array
    {
        if (isset($this->getRequest()->nlu->tokens)) {
            return $this->getRequest()->nlu->tokens;
        } else {
            throw new RequestException('nluTokens is not defined');
        }
    }

    /**
     * Возвращает true, если хотя бы одно слово из аргументов находится в nluTokens
     *
     * @param ...$values
     * @return bool result
     * @throws RequestException
     */
    public function existInTokens(...$values): bool
    {
        return count(array_intersect($values, $this->getNluTokens())) > 0;
    }
}