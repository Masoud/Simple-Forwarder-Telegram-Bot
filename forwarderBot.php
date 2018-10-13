<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'vendor/autoload.php';
$command = file_get_contents("php://input");
$json = json_decode($command, true);
$headers = ['Accept' => 'application/json'];
// Get Chat id and Text
$id = $json['message']['from']['id'];
$username = $json['message']['from']['username'];
$text = $json['message']['text'];
// Set Timezone
$botToken = '667322908:AAGDKzF1VFMGeGhH2W1qXFJkdHQeIS78aSk';
if ($text != '/start') {
    $message = "از آی‌دی: " . $id . "\n \n" .
        "با یوزرنیم: @" . $username . "\n \n" .
        "این پیام رو فرستاده: " . $text;
    $message2='ممنون از ارتباط شما با تیم مک نید، به زودی به شما پیام خواهیم داد. 🙏';
    $text = [
        'chat_id' => '74415978',
        'text' => $message,
        'parse_mode' => 'html',
    ];
    $text2 = [
        'chat_id' => $id,
        'text' => $message2,
        'parse_mode' => 'html',
    ];
    $response = Unirest\Request::post('https://api.telegram.org/bot' . $botToken . '/sendMessage', $headers, $text);
    $response2 = Unirest\Request::post('https://api.telegram.org/bot' . $botToken . '/sendMessage', $headers, $text2);
}
