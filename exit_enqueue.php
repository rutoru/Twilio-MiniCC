<?php
/**
 * ミニコールセンターサービスでQueue(Enqueue)から出た後にデータ収集するexit_enqueue.php
 * お客様がキューから出る際の情報を`enqueue_data`テーブルに格納
 *
 * @author rutoru
 * @package Twilio-MiniCC
 * @GitHub  https://github.com/rutoru/Twilio-MiniCC
 */
// DB接続クラス
require_once 'Database.php';
// 設定クラス
require_once 'Conf.php';
// Twilio Helperライブラリ（index.phpと同じ場所にServicesフォルダが存在する前提）
require_once 'Services/Twilio.php';

try{

    // INSERT
    $db = new Database();
    $stmt = $db->getPdo()->prepare(
            'INSERT INTO `enqueue_data`(`CallSid`, `From`, `To`, `CallStatus`, `ApiVersion`, `Direction`, `ForwardedFrom`, `CallerName`, `QueueResult`, `QueueSid`, `QueueTime`, `Time`)'
            . ' VALUES (:CallSid,:From,:To,:CallStatus,:ApiVersion,:Direction,:ForwardedFrom,:CallerName,'
            . ':QueueResult,:QueueSid,:QueueTime,NOW())');

    // Standard Parameters
    $stmt->bindValue(':CallSid', filter_input(INPUT_POST,'CallSid'));
    $stmt->bindValue(':From', filter_input(INPUT_POST,'From'));
    $stmt->bindValue(':To', filter_input(INPUT_POST,'To'));
    $stmt->bindValue(':CallStatus', filter_input(INPUT_POST,'CallStatus'));
    $stmt->bindValue(':ApiVersion', filter_input(INPUT_POST,'ApiVersion'));
    $stmt->bindValue(':Direction', filter_input(INPUT_POST,'Direction'));
    $stmt->bindValue(':ForwardedFrom', filter_input(INPUT_POST,'ForwardedFrom'));
    $stmt->bindValue(':CallerName', filter_input(INPUT_POST,'CallerName'));
    // Queue Parameters
    $stmt->bindValue(':QueueResult', filter_input(INPUT_POST,'QueueResult'));
    $stmt->bindValue(':QueueSid', filter_input(INPUT_POST,'QueueSid'));
    $stmt->bindValue(':QueueTime', filter_input(INPUT_POST,'QueueTime'));
    $stmt->execute();

} catch (Exception $e) {

    // デバッグモード時はエラーメッセージ表示
    // 運用時はデータ取得に失敗しても、オペレータに接続
    if(Conf::DEBUG){
        echo $e->getMessage();
    }

}

// Twimlオブジェクト作成
$response = new Services_Twilio_Twiml();

// TwiML作成
// 何もレスポンスが無いとオペレータから切断したときエラーメッセージになるため
print $response;
