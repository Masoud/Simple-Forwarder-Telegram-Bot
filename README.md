# Simple Forwarder Telegram Bot
[![Build Status](https://travis-ci.org/Masoud/Simple-Forwarder-Telegram-Bot.svg)](https://travis-ci.org/Masoud/Simple-Forwarder-Telegram-Bot)

This Telegram Bot can send any message you want to your own User ID.

Also It includes inline-keyboard-button and saves their data and it sends it to Admin User ID

# Installation
To have latest updates with ease, use this command on terminal to get a clone:

```bash
git clone https://github.com/Masoud/Simple-Forwarder-Telegram-Bot
```
#### After Clone
1- First you need to create a bot with [botFather](https://telegram.me/BotFather) and remember your Token.

2- Then, replace your Token in the forwarderBot.php file.

3- Obtain your admin ID (intended to send users messages) via the [@get_id_bot](https://telegram.me/get_id_bot) and replace it in the forwarderBot.php.

4- Enable your Webhook. (How to enable [Webhook?](https://core.telegram.org/bots/api#setwebhook))

5- You can now use your bot.

# Usage
Just read inline help to find what each function does.

```php
sendToTelegram($text); // Send any text to your bot

    $adminChatID = 'xxxx';
    $current= 'Some Text';
    $textToAdmin = [
        'chat_id' => $adminChatID,
        'text' => $current,
        'parse_mode' => 'html',
    ];
    sendToTelegram($textToAdmin);
```
```php
messageToUser($user_id, $month); // Send any message to any user

    $UserId = $json['callback_query']['from']['id'];
    $month = "1 Month";
    messageToUser($UserId, $month);
```
```php    
saveData($user_id, $user_name, $month); // Save user's information
usernamePassword(); // Save username and password "specific on this project" 
```
# License
> MIT