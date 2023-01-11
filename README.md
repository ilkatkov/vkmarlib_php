# VK Marusia Library

В данном пакете представлена библиотека для работы с навыками/скиллами Маруси.

Для работы библиотеки требуется PHP версии не ниже 7.4.

## Оглавление
- [Установка](#установка)
- [Начало работы](#начало-работы)
- [Классы и их методы](#классы-и-их-методы)
    - [Skill](#skill)
      - [Вывод данных](#вывод-данных)
      - [Обработка информации от клиента](#обработка-информации-от-клиента)
      - [Работа с сессиями](#работа-с-сессиями)
      - [Работа с данными пользователя](#работа-с-данными-пользователя)
    - [Card](#card)
- [Обработка ошибок](#обработка-ошибок)
- [Примеры](#примеры)

## Установка

Установить библиотеку можно с помощью composer:

```
composer require ilkatkov/vkmarlib_php
```

## Начало работы

Для начала использования вам необходимо создать объект библиотеки:

```php
$content = file_get_contents('php://input');
$m = new VKMarLib\Skill($content);
```
```$content``` - запрос от Маруси, представленный в виде строки JSON. В примерах передается ```'php://input'```.

Далее с этим объектом можно работать, используя его методы, например, задать текст ответа при вызове вашего скилла:
```php
$m->setResponseText("Hello, world!");
$m->setEndSession(); // устанавливаем конец сессии
echo $m->getResponseJson(); // вывод ответа вебхука
```

## Классы и их методы

Библиотека обновляется и на данный момент поддерживает основные методы работы с навыками Маруси:

### Skill

#### Вывод данных
| Метод                                       | Описание                                                           |
|---------------------------------------------|--------------------------------------------------------------------|
| ```setResponseText(string $text)```         | Устанавливает текст ответа                                         |
| ```setResponseTts(string $tts)```           | Устанавливает TTS ответа                                           |
| ```setPush(string $text, array $payload)``` | Устанавливает пуш уведомление с текстом $text и нагрузкой $payload |
| ```addResponseButton(string $title)```      | Добавляет кнопку с именем $title                                   |
| ```addResponseButtons(array $titles)```     | Добавляет кнопки с именами из массива $titles                      |
| ```addCard(Card $card)```                   | Добавляет заполненную карточку в ответ                             |
| ```getResponseJson()```                     | Формирует и возвращает JSON ответ для Маруси                       |

#### Обработка информации от клиента
| Метод                                                    | Описание                                                                                     |
|----------------------------------------------------------|----------------------------------------------------------------------------------------------|
| ```getNluTokens()```                                     | Возвращает распознанные слова в виде массива строк                                           |
| ```existInNluTokens(...$values)```                       | Возвращает true, если  хотя бы одно слово из аргументов находится в nluTokens, иначе - false |
| ```getClientCity()```                                    | Возвращает название города клиента на русском языке                                          |
| ```getClientLocale()```                                  | Возвращает языковой стандарт клиента                                                         |
| ```getClientTimezone()```                                | Возвращает часовой пояс клиента                                                              |

#### Работа с сессиями

| Метод                                              | Описание                                                              |
|----------------------------------------------------|-----------------------------------------------------------------------|
| ```setEndSession()```                              | Устанавливает конец сессии                                            |
| ```getSessionStates()```                           | Возвращает состояния сессии (session state) запроса                   |
| ```setResponseSessionState(string $key, $value)``` | Устанавливает значение $value по ключу $key в массив состояний сессии |
| ```delResponseSessionState(string $key)```         | Удаляет состояние сессии по ключу $key                                |
| ```clearResponseSessionState()```                  | Очищает состояния сессии                                              |

#### Работа с данными пользователя

| Метод                                           | Описание                                                                    |
|-------------------------------------------------|-----------------------------------------------------------------------------|
| ```getUserStates()```                           | Возвращает состояния пользователя (user state) запроса                      |
| ```setResponseUserState(string $key, $value)``` | Устанавливает значение $value по ключу $key в массив состояний пользователя |
| ```delResponseUserState(string $key)```         | Удаляет состояние пользователя по ключу $key                                |
| ```clearResponseUserState()```                  | Очищает состояния пользователя                                              |

### Card

Объект класса для работы с карточками создается следующим образом:
```php
use VKMarLib\Classes\Card;

$cardType = "BigImage";
$card = new Card($cardType);
```
, где входной параметр ```$type``` может принимать значения "BigImage", "ItemsList", "MiniApp" или "Link". 

| Метод                          | Описание                                           |
|--------------------------------|----------------------------------------------------|
| ```setImageId(int $imageId)``` | Устанавливает изображение для карточки             |
| ```addImageId(int $imageId)``` | Добавляет изображение с imageId в список ItemsList |
| ```setUrl(string $url)```      | Устанавливает ссылку для карточки                  |
| ```setTitle(string $title)```  | Устанавливает заголовок для карточки Link          |
| ```setText(string $text)```    | Устанавливает текст для карточки Link              |
| ```getCard()```                | Возвращает заполненную карточку в виде массива     |

## Обработка ошибок

| Тип                                                      | Условия                                                               |
|----------------------------------------------------------|-----------------------------------------------------------------------|
| ```VKMarLib\Exceptions\ResponseException```              | Ошибка формирования ответа для Маруси                                 |
| ```VKMarLib\Exceptions\RequestException```               | Ошибка чтения запроса от Маруси                                       |
| ```VKMarLib\Exceptions\ValidationException```            | Нарушение валидации данных для ответа Марусе                          |
| ```VKMarLib\Exceptions\ArgumentException```              | Передача неверного параметра в методах формирования ответа для Маруси |
| ```VKMarLib\Exceptions\NotAvailableForActionException``` | Метод не может быть вызван                                            |

## Примеры

Все примеры можно найти в директории ```/examples```.

## License

MIT
