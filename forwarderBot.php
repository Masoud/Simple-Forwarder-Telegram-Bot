<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'vendor/autoload.php';
$command = file_get_contents("php://input");
$json = json_decode($command, true);
$headers = ['Accept' => 'application/json'];
$id = $json['message']['from']['id'];
$username = $json['message']['from']['username'];
$text = $json['message']['text'];
$MID = $json['message']['message_id'];
$callback = $json['callback_query']['data'];
$botToken = '625923840:AAE6gR1V6orE8gsF-QE2ExnrZNUQUvRJ7fQ';
function sendToTelegram($text)
{
    Unirest\Request::post('https://api.telegram.org/bot' . $GLOBALS['botToken'] . '/sendMessage', $GLOBALS['headers'], $text);
}

function messageToUser($user_id, $month)
{
    $text = [
        'chat_id' => $user_id,
        'text' => "شما «خرید وی‌پی‌ان " . $month . "» را انتخاب کرده‌اید.",
        'parse_mode' => 'html',
    ];
    sendToTelegram($text);
    $message = '
    نام کاربری دلخواه خود را ارسال کنید
    ';
    $SendUserName = [
        'chat_id' => $user_id,
        'text' => $message,
        'parse_mode' => 'html',
    ];
    sendToTelegram($SendUserName);
    if (isset($text)) {
        $submitUsername = "
        نام کاربری شما با عنوان <strong>" . $text . "</strong> ثبت شد.
        ";
        $textToSubMit = [
            'chat_id' => $user_id,
            'text' => $submitUsername,
            'parse_mode' => 'html',
        ];
        sendToTelegram($textToSubMit);
        $ThanksText = [
            'chat_id' => $user_id,
            'text' => "با تشکر از ارتباط شما، به زودی با شما در تماس خواهیم بود. 🙏",
            'parse_mode' => 'html',
        ];
        sendToTelegram($ThanksText);
    }
}

function messageToUserForPlan($user_id)
{
    $message = "- یک ماهه: ۲۰.۰۰۰ تومان
   
- سه ماهه: ۵۰.۰۰۰ تومان

- شش ماهه: ۸۰.۰۰۰ تومان

- یک ساله: ۱۳۰.۰۰۰ تومان";
    $text = [
        'chat_id' => $user_id,
        'text' => $message,
        'parse_mode' => 'html',
    ];
    sendToTelegram($text);
}

function messageToAdmin($user_id, $user_name, $month)
{
    $message = "از آی‌دی: " . $user_id . "\n \n" .
        "با یوزرنیم: @" . $user_name . "\n \n" .
        "وی‌پی‌ان " . $month . " میخواد";
    $adminChatID = 'xxxx';
    $textToAdmin = [
        'chat_id' => $adminChatID,
        'text' => $message,
        'parse_mode' => 'html',
    ];
    sendToTelegram($textToAdmin);
}

if ($text == '/start') {
    $text = [
        'chat_id' => $id,
        'text' => 'قصد خرید کدام پلن را دارید؟',
        'parse_mode' => 'html',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [
                    [
                        'text' => 'وی‌پی‌ان ۱ ماهه',
                        'callback_data' => 1
                    ],
                    [
                        'text' => 'وی‌پی‌ان ۳ ماهه',
                        'callback_data' => 3
                    ],
                ], [
                    [
                        'text' => 'وی‌پی‌ان ۶ ماهه',
                        'callback_data' => 6
                    ],
                    [
                        'text' => 'وی‌پی‌ان ۱۲ ماهه',
                        'callback_data' => 12
                    ]
                ],
                [
                    [
                        'text' => 'اطلاع از قیمت‌ها',
                        'callback_data' => 13
                    ],
                ]
            ]
        ])
    ];
    sendToTelegram($text);
}
if (isset($callback)) {
    $UserId = $json['callback_query']['from']['id'];
    $UserName = $json['callback_query']['from']['username'];
    if ($callback == '1') {
        $month = "۱ ماهه";
        messageToUser($UserId, $month);
        messageToAdmin($UserId, $UserName, $month);
    } else if ($callback == '3') {
        $month = "۳ ماهه";
        messageToUser($UserId, $month);
        messageToAdmin($UserId, $UserName, $month);
    } else if ($callback == '6') {
        $month = "۶ ماهه";
        messageToUser($UserId, $month);
        messageToAdmin($UserId, $UserName, $month);
    } else if ($callback == '12') {
        $month = "۱۲ ماهه";
        messageToUser($UserId, $month);
        messageToAdmin($UserId, $UserName, $month);
    } else if ($callback == '13') {
        messageToUserForPlan($UserId);
    }
}