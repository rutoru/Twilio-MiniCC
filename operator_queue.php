<?php
/**
 * オペレータにQueueを提供するoperator_queue.php
 * オペレータをキューに入れ、繰り返し着信を取る
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

// キューに入れる＝受電開始
$response->say("キューにはいりました。", array('language' => Conf::LANG));
$dial = $response->dial();
$dial->queue(Conf::QUEUE, array('url' => 'guidance.php', Conf::METHOD));
// オペレータは繰り返し受電
$response->redirect();

// TwiML作成
print $response;




