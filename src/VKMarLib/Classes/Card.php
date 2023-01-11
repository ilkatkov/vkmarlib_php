<?php

namespace VKMarLib\Classes;

use VKMarLib\Exceptions\ArgumentException;
use VKMarLib\Exceptions\NotAvailableForActionException;
use VKMarLib\Exceptions\ValidationException;

/**
 * Класс для работы с карточками
 *
 * @link https://dev.vk.com/marusia/cards
 */
class Card
{

    private string $type;
    private int $imageId;
    private array $items;
    private string $url;
    private string $title;
    private string $text;

    /**
     * Создает объект карточки для ответа Маруси
     *
     * @link https://dev.vk.com/marusia/cards
     * @param string $type "BigImage", "ItemsList", "MiniApp" или "Link"
     * @throws ArgumentException
     */
    public function __construct(string $type)
    {
        switch ($type) {
            case "MiniApp":
            case "ItemsList":
            case "BigImage":
            case "Link":
                $this->type = $type;
                break;
            default:
                throw new ArgumentException("unknown \"type\" for Card");
        }
    }

    /**
     * Устанавливает изображение для карточки
     *
     * @param int $imageId ID изображения из раздела Медиафайлы в настройках скилла
     * @return void
     * @throws NotAvailableForActionException
     * @throws ValidationException
     */
    public function setImageId(int $imageId): void
    {
        if ($this->type == "BigImage" || $this->type == "Link") {
            if ($imageId > 0) {
                $this->imageId = $imageId;
            } else {
                throw new ValidationException("imageId for \"{$this->type}\" should be > 0");
            }
        } else {
            throw new NotAvailableForActionException("this method cannot be called in \"{$this->type}\"");
        }
    }

    /**
     * Добавляет изображение с imageId в список ItemsList
     *
     * @link https://dev.vk.com/marusia/cards#Набор%20изображений
     * @param int $imageId ID изображения из раздела Медиафайлы в настройках скилла
     * @return void
     * @throws NotAvailableForActionException
     * @throws ValidationException
     */
    public function addImageId(int $imageId): void
    {
        if ($this->type == "ItemsList") {
            if ($imageId > 0) {
                $this->items[] = array("image_id" => $imageId);
            } else {
                throw new ValidationException("imageId for \"{$this->type}\" should be > 0");
            }
        } else {
            throw new NotAvailableForActionException("this method cannot be called in \"{$this->type}\"");
        }
    }

    /**
     * Устанавливает ссылку для карточки
     *
     * @param string $url ссылка
     * @return void
     * @throws ValidationException
     * @throws NotAvailableForActionException
     */
    public function setUrl(string $url): void
    {
        if ($this->type == "MiniApp" || $this->type == "Link") {
            if (strlen($url) > 0) {
                $this->url = $url;
            } else {
                throw new ValidationException("length of url in \"{$this->type}\" should be > 0");
            }
        } else {
            throw new NotAvailableForActionException("this method cannot be called in \"{$this->type}\"");
        }
    }

    /**
     * Устанавливает заголовок для карточки Link
     *
     * @param string $title заголовок
     * @return void
     * @throws ValidationException
     * @throws NotAvailableForActionException
     */
    public function setTitle(string $title): void
    {
        if ($this->type == "Link") {
            if (strlen($title) > 0) {
                $this->title = $title;
            } else {
                throw new ValidationException("length of title in \"{$this->type}\" should be > 0");
            }
        } else {
            throw new NotAvailableForActionException("this method cannot be called in \"{$this->type}\"");
        }
    }

    /**
     * Устанавливает текст для карточки Link
     *
     * @param string $text текст
     * @return void
     * @throws ValidationException
     * @throws NotAvailableForActionException
     */
    public function setText(string $text): void
    {
        if ($this->type == "Link") {
            if (strlen($text) > 0) {
                $this->text = $text;
            } else {
                throw new ValidationException("length of text in \"{$this->type}\" should be > 0");
            }
        } else {
            throw new NotAvailableForActionException("this method cannot be called in \"{$this->type}\"");
        }
    }

    /**
     * Возвращает заполненную карточку в виде массива
     *
     * @return array
     * @throws ValidationException
     */
    public function getCard(): array
    {
        $card = array("type" => $this->type);

        if ($this->type == 'BigImage' || $this->type == "Link") {
            if (isset($this->imageId)) {
                $card["image_id"] = $this->imageId;
            } else {
                throw new ValidationException("\"{$this->type}\" must have imageId");
            }
        }

        if ($this->type == "ItemsList") {
            if (isset($this->items)) {
                $card["items"] = $this->items;
            } else {
                throw new ValidationException("\"{$this->type}\" cannot be empty");
            }
        }

        if ($this->type == "MiniApp" || $this->type == "Link") {
            if (isset($this->url)) {
                $card["url"] = $this->url;
            } else {
                throw new ValidationException("\"{$this->type}\" must have URL");
            }
        }

        if ($this->type == "Link") {
            if (isset($this->title)) {
                $card["title"] = $this->title;
            } else {
                throw new ValidationException("\"{$this->type}\" must have title");
            }
            if (isset($this->text)) {
                $card["text"] = $this->text;
            } else {
                throw new ValidationException("\"{$this->type}\" must have text");
            }
        }

        return $card;
    }

}
