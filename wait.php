<?php
/**
 * ミニコールセンターサービスでキューイング時にメッセージを送出するwait.php
 *
 * @author rutoru
 * @package Twilio-MiniCC
 * @GitHub  https://github.com/rutoru/Twilio-MiniCC
 */
// 設定クラス
require_once 'Conf.php';
// Twilio Helperライブラリ（index.phpと同じ場所にServicesフォルダが存在する前提）
require_once 'Services/Twilio.php';

// Twimlオブジェクト作成
$response = new Services_Twilio_Twiml();

// QueuePosition取得（数字のみにフィルタリング）
$waitnumber = filter_input(INPUT_POST,'QueuePosition',FILTER_SANITIZE_NUMBER_INT);

// 待ち（短めの保留音を入れる予定）
$response->pause('3');

// キューイングメッセージ送出
$response->say("お待たせしております。現在、".$waitnumber."番目にお待ちです。",
                array('language' => Conf::LANG));

// 保留音送出
$response->play(Conf::MOH_LONG);

// TwiML作成
print $response;
