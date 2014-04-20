<?php
/**
 * ミニコールセンターサービスで通話終了後にデータ収集するstatus_callback.php
 * 通話終了後に情報をstatuscallback_dataテーブルに格納
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
            'INSERT INTO `statuscallback_data`(`CallSid`, `From`, `To`, `CallStatus`, `ApiVersion`, `Direction`, `ForwardedFrom`, `CallerName`, `CallDuration`, `RecordingUrl`, `RecordingSid`, `RecordingDuration`, `Time`)'
            . ' VALUES (:CallSid,:From,:To,:CallStatus,:ApiVersion,:Direction,:ForwardedFrom,:CallerName,'
            . ':CallDuration,:RecordingUrl,:RecordingSid,:RecordingDuration,NOW())');

    // Standard Parameters
    $stmt->bindValue(':CallSid', filter_input(INPUT_POST,'CallSid'));
    $stmt->bindValue(':From', filter_input(INPUT_POST,'From'));
    $stmt->bindValue(':To', filter_input(INPUT_POST,'To'));
    $stmt->bindValue(':CallStatus', filter_input(INPUT_POST,'CallStatus'));
    $stmt->bindValue(':ApiVersion', filter_input(INPUT_POST,'ApiVersion'));
    $stmt->bindValue(':Direction', filter_input(INPUT_POST,'Direction'));
    $stmt->bindValue(':ForwardedFrom', filter_input(INPUT_POST,'ForwardedFrom'));
    $stmt->bindValue(':CallerName', filter_input(INPUT_POST,'CallerName'));
    // StatusCallback Parameters
    $stmt->bindValue(':CallDuration', filter_input(INPUT_POST,'CallDuration'));
    $stmt->bindValue(':RecordingUrl', filter_input(INPUT_POST,'RecordingUrl'));
    $stmt->bindValue(':RecordingSid', filter_input(INPUT_POST,'RecordingSid'));
    $stmt->bindValue(':RecordingDuration', filter_input(INPUT_POST,'RecordingDuration'));
    $stmt->execute();

} catch (Exception $e) {

    // デバッグモード時はエラーメッセージ表示
    // 運用時はデータ取得に失敗しても、オペレータに接続
    if(Conf::DEBUG){
        echo $e->getMessage();
    }

}

