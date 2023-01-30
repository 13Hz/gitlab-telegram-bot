![image](https://user-images.githubusercontent.com/39442071/213875462-0756d564-ac1f-43c8-b533-9d218f4cfedc.png)

## Интеграция Gitlab и Telegram-bot API

Телеграм-бот для оповещения об операциях, совершенных в установленных Gitlab репозиториях

## Возможности

Перечень триггеров из Gitlab:
- [ ] Push events
- [ ] Tag push events
- [X] Comments
- [ ] Confidential comments
- [X] Issues events
- [ ] Confidential issues events
- [X] Merge request events
- [ ] Job events
- [ ] Pipeline events
- [ ] Wiki page events
- [ ] Deployment events
- [ ] Feature flag events
- [ ] Releases events

## Установка и настройка

1. Скопировать репозиторий
```
git clone https://github.com/13Hz/gitlab-telegram-bot
```
2. Установить `composer` зависимости
```
composer install
```
3. Создать `.env` файл
```
cp .env.example .env
```
4. Создать телеграм-бот с помощью [@BotFather](https://t.me/BotFather)
5. В Gitlab репозитории переходим в раздел `Webhooks` и создаем вебхук. Ссылка должна быть следующего формата: `https://example.com/hook`. 
Здесь же нужно настроить триггеры и установить секретный токен
6. Настроить `.env` файл, указав актульные данные для подключения к БД, параметры созданного бота (токен, имя), а так же токен Gitlab вебхука
7. Сгенерировать ключ
```
php artisan key:generate
```
8. Создать символическую ссылку на каталог хранилища
```
php artisan storage:link
```
9. Создать таблицы
```
php artisan migrate --seed
```
10. Установить ссылку на вебхук для бота (**ВАЖНО!** для работы вебхука обязателен ssl сертификат). Здесь https://example.com/ - ссылка, по адресу которой расположен бот
```
php artisan telegram:webhook https://example.com/hook
```

После настройки серверной части можно приступать добавлению чатов и ссылок на репозитории для оповещений. Для этого можно воспользоваться командой `/add <ссылка>`

Эта команда установит связь текущего чата и репозитория по указанной ссылке, теперь, если вебхук Gitlab был настроен правильно, бот начнет писать в установленном чате о произошедших действиях

## Локальный запуск

Что бы запустить бота локально и потестировать его функционал можно воспользоваться [ngrok](https://ngrok.com/) для открытия вашего локального веб-сервера в общий доступ. Плюсом использования `ngrok` станет https соединение, необходимое для организации Webhook работы Telegram api
