# ![image](https://user-images.githubusercontent.com/39442071/234793633-dc38d35e-6b8e-420a-92b6-744efb69eb80.png)
[![Actions](https://img.shields.io/github/actions/workflow/status/13Hz/gitlab-telegram-bot/tests.yaml?label=Tests)](https://github.com/13Hz/gitlab-telegram-bot/actions)
[![GitHub issues](https://img.shields.io/github/issues-raw/13Hz/gitlab-telegram-bot)](https://github.com/13Hz/gitlab-telegram-bot/issues)
[![GitHub commit activity](https://img.shields.io/github/commit-activity/m/13Hz/gitlab-telegram-bot)](https://github.com/13Hz/gitlab-telegram-bot/commits/main)
[![GitHub](https://img.shields.io/github/license/13Hz/gitlab-telegram-bot)](https://github.com/13Hz/gitlab-telegram-bot/blob/main/LICENSE)
[![Telegram](https://img.shields.io/static/v1?label=telegram&message=@not1s_bot&color=279fda)](https://t.me/not1s_bot)

[Русский](https://github.com/13Hz/gitlab-telegram-bot/blob/main/README.md) | [English](https://github.com/13Hz/gitlab-telegram-bot/blob/main/README.eng.md)

Telegram bot to notify about operations performed in installed Gitlab repositories. Instantly sends notifications to private and public chats.

## Possibilities

List of triggers from Gitlab:
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

In addition to notifications, you can configure the chat bot directly in the telegram chat where the bot is present.
List of available commands:
```
/add - Add a gitlab repository to receive notifications
/list - Listing added repositories
/help - List of available commands
/start - Registering a chat and launching a bot
```
The main command for setting up related Gitlab repositories is `/list`, in addition to listing, you can delete repositories [#5](https://github.com/13Hz/gitlab-telegram-bot/pull/5) and set filtering of received notifications by trigger type [#12](https://github.com/13Hz/gitlab-telegram-bot/pull/12)

![image](https://user-images.githubusercontent.com/39442071/234793687-73655f61-8bc2-4365-8d9f-f0f338c8fb11.png)

## Installation and setup

1. Copy repository
```
git clone https://github.com/13Hz/gitlab-telegram-bot
```
2. Install `composer` dependencies
```
composer install
```
3. Create `.env` file
```
cp .env.example .env
```
4. Create a telegram bot with [@BotFather](https://t.me/BotFather)
5. In the Gitlab repository, go to the `Webhooks` section and create a webhook. The link must be in the following format: `https://example.com/hook`.
   Here you need to configure triggers and set a secret token
6. Set up the `.env` file, specifying the actual data for connecting to the database, the parameters of the created bot (token, name), as well as the Gitlab webhook token
7. Generate key
```
php artisan key:generate
```
8. Create a symbolic link to the storage directory
```
php artisan storage:link
```
9. Create tables
```
php artisan migrate --seed
```
10. Set a link to the webhook for the bot (**IMPORTANT!** an ssl certificate is required for the webhook to work).
```
php artisan telegram:webhook
```

After setting up the backend, you can start adding chats and links to repositories for notifications. To do this, you can use the command `/add <link>`

This command will connect the current chat and the repository at the specified link, now, if the Gitlab webhook has been configured correctly, the bot will start writing in the installed chat about the actions that have taken place

## Local launch

To run the bot locally and test its functionality, you can use [ngrok](https://ngrok.com/) to share your local web server. The advantage of using `ngrok` will be the https connection required to organize the Webhook of the Telegram api
