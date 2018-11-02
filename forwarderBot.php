<?php

// Error Logs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load Libraries
require_once 'vendor/autoload.php';

// Get Webhook Telegram
$command = file_get_contents("php://input");
$json = json_decode($command, true);
$headers = ['Accept' => 'application/json'];

// Get Data
$id = $json['message']['from']['id'];
$username = $json['message']['from']['username'];
$text = $json['message']['text'];
$MID = $json['message']['message_id'];
$callback = $json['callback_query']['data'];

// Your Bot Token
$botToken = '625923840:AAG0wtK64xgTK2JULcLAU4VC9I_hQsleCcM';

function sendToTelegram($text)
{
    Unirest\Request::post('https://api.telegram.org/bot' . $GLOBALS['botToken'] . '/sendMessage', $GLOBALS['headers'], $text);
}

function messageToUser($user_id, $month)
{
    $text2 = [
        'chat_id' => $user_id,
        'text' => "شما «خرید وی‌پی‌ان " . $month . "» را انتخاب کرده‌اید.",
        'parse_mode' => 'html',
    ];
    sendToTelegram($text2);
    $message = '
    برای انتخاب نام کاربری دلخواه بر روی متن زیر کلیک کنید:
    /Username
    ';
    $SendUserName = [
        'chat_id' => $user_id,
        'text' => $message,
        'parse_mode' => 'html',
    ];
    sendToTelegram($SendUserName);
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

function saveData($user_id, $user_name, $month)
{
    $current = file_get_contents("info.txt");
    $message = "
        🆔: " . $user_id . "
        
👤 با یوزرنیم: @" . $user_name . "
🔒 وی‌پی‌ان: " . $month . "
        ";
    $current = file_get_contents("info.txt");
    $data_to_write .= $current . " " . $message . "\n";
    file_put_contents('info.txt', $data_to_write);
}

function usernamePassword()
{
    $current = file_get_contents("info.txt");
    $adminChatID = '74415978';
    $textToAdmin = [
        'chat_id' => $adminChatID,
        'text' => $current,
        'parse_mode' => 'html',
    ];
    sendToTelegram($textToAdmin);
    $info = fopen("info.txt", "w");
    fwrite($info, null);
    fclose($info);
}

// Start
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
} else if ($text == '/Username') { // Get Username
    $myfile = fopen("isIt.txt", "w");
    $nextNumber = 1;
    fwrite($myfile, $nextNumber);
    fclose($myfile);
    $sendUser = [
        'chat_id' => $id,
        'text' => 'یوزرنیم خودتون رو انتخاب کنید',
        'parse_mode' => 'html',
    ];
    sendToTelegram($sendUser);
} else if ($text == '/Password') { // Get Password
    $myfile = fopen("isIt.txt", "w");
    $nextNumber = 2;
    fwrite($myfile, $nextNumber);
    fclose($myfile);
    $sendUser = [
        'chat_id' => $id,
        'text' => 'پسورد خودتون رو انتخاب کنید',
        'parse_mode' => 'html',
    ];
    sendToTelegram($sendUser);
} else {
    $file = 'isIt.txt';
    $f = fopen($file, 'r');
    $number = fgets($f);
    $number = (int)$number;
    if ($number == 1) {
        $username = $text;
        $current = file_get_contents("info.txt");
        $data_to_write .=
            $current . "        
〽️ یوزرنیم: " . $username . "";
        file_put_contents('info.txt', $data_to_write);

        $submitUsername = "
        نام کاربری شما با عنوان <strong>" . $text . "</strong> ثبت شد.
        ";
        $textToSubMit = [
            'chat_id' => $id,
            'text' => $submitUsername,
            'parse_mode' => 'html',
        ];
        sendToTelegram($textToSubMit);
        $message = '
    برای انتخاب پسورد دلخواه بر روی متن زیر کلیک کنید:
    /Password
    ';
        $SendUserName = [
            'chat_id' => $id,
            'text' => $message,
            'parse_mode' => 'html',
        ];
        sendToTelegram($SendUserName);
    } else if ($number == 2) {
        $password = $text;
        $current = file_get_contents("info.txt");
        $data_to_write .=
            $current . "
            🔱 پسورد: " . $password . "
            ";
        file_put_contents('info.txt', $data_to_write);
        $submitPassword = "
        پسورد شما با عنوان <strong>" . $text . "</strong> ثبت شد.
        ";
        $textToSubMit = [
            'chat_id' => $id,
            'text' => $submitPassword,
            'parse_mode' => 'html',
        ];
        sendToTelegram($textToSubMit);
        $ThanksText = [
            'chat_id' => $id,
            'text' => "با تشکر از ارتباط شما، به زودی با شما در تماس خواهیم بود. 🙏",
            'parse_mode' => 'html',
        ];
        sendToTelegram($ThanksText);

        // Send Data for Admin
        usernamePassword();
    }
    fclose($f);
    $myfile = fopen("isIt.txt", "w");
    $nextNumber = 0;
    fwrite($myfile, $nextNumber);
    fclose($myfile);
}

if (isset($callback)) {
    $UserId = $json['callback_query']['from']['id'];
    $UserName = $json['callback_query']['from']['username'];
    if ($callback == '1') {
        $month = "۱ ماهه";
        messageToUser($UserId, $month);
        saveData($UserId, $UserName, $month);
    } else if ($callback == '3') {
        $month = "۳ ماهه";
        messageToUser($UserId, $month);
        saveData($UserId, $UserName, $month);
    } else if ($callback == '6') {
        $month = "۶ ماهه";
        messageToUser($UserId, $month);
        saveData($UserId, $UserName, $month);
    } else if ($callback == '12') {
        $month = "۱۲ ماهه";
        messageToUser($UserId, $month);
        saveData($UserId, $UserName, $month);
    } else if ($callback == '13') {
        messageToUserForPlan($UserId);
    }
}