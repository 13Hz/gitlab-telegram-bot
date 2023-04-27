# ![image](https://user-images.githubusercontent.com/39442071/234793633-dc38d35e-6b8e-420a-92b6-744efb69eb80.png)
![GitHub issues](https://img.shields.io/github/issues-raw/13Hz/gitlab-telegram-bot)
![GitHub commit activity](https://img.shields.io/github/commit-activity/m/13Hz/gitlab-telegram-bot)
![GitHub](https://img.shields.io/github/license/13Hz/gitlab-telegram-bot)
[![Telegram](https://img.shields.io/static/v1?label=telegram&message=@not1s_bot&color=279fda)](https://t.me/not1s_bot)

Телеграм-бот для оповещения об операциях, совершенных в установленных Gitlab репозиториях. Мнгновенно отправляет уведомления в приватные и общие чаты.

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
- [X] Pipeline events
- [ ] Wiki page events
- [ ] Deployment events
- [ ] Feature flag events
- [ ] Releases events

Помимо уведомлений можно настраивать работу чат бота непосредственно в телеграм чате, где присутствует бот.
Список доступных команд:
```
/add - Добавить гитлаб репозиторий для получения уведомлений
/list - Вывод списка добавленных репозиториев
/help - Список доступных команд
/start - Регистрация чата и запуск бота
```
Основной командой для настройки связанных Gitlab репозиториев считается `/list`, кроме вывода списка можно удалять репозитори [#5](https://github.com/13Hz/gitlab-telegram-bot/pull/5) и устанавливать фильтрацию получаемых уведомлений по типу триггеров [#12](https://github.com/13Hz/gitlab-telegram-bot/pull/12)

![image](https://user-images.githubusercontent.com/39442071/234793687-73655f61-8bc2-4365-8d9f-f0f338c8fb11.png)

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
