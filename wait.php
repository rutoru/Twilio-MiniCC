<?php
/**
 * ミニコールセンターサービスでキューイング時にメッセージを送出するwait.php
 *
 * @author rutoru
 * @package Twilio-MiniCC
 */
require_once 'Database.php';
require_once 'Services/Twilio.php';

// Twimlオブジェクト作成
$response = new Services_Twilio_Twiml();

// QueuePosition取得（数字のみにフィルタリング）
$waitnumber = filter_input(INPUT_POST,'QueuePosition',FILTER_SANITIZE_NUMBER_INT);

// キューイングメッセージ送出
$response->say("お待たせしております。現在、"
                .$waitnumber."番目にお待ちです。しばらくお待ちください。",
                array('language' => 'ja-jp'));

// 保留音送出
$response->play('http://com.twilio.sounds.music.s3.amazonaws.com/MARKOVICHAMP-Borghestral.mp3');

// TwiML作成
print $response;
