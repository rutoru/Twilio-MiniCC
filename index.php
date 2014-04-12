<?php   // index.php
/**
 * ミニコールセンターサービスを提供するindex.php（IVR・キューイング・情報提供）
 *
 * @author rutoru
 * @package Twilio-MiniCC
 */
// DB接続クラス
require_once 'Database.php';
// Twilio Helperライブラリ（index.phpと同じ場所にServicesフォルダが存在する前提）
// https://jp.twilio.com/docs/libraries
// http://twilio-php.readthedocs.org/en/latest/
require_once 'Services/Twilio.php';

// Twimlオブジェクト作成
$response = new Services_Twilio_Twiml();

// POSTで電話入力（DTMF入力）値が送られてきているかどうかの判定。初回アクセス時はelseへ
// isset()は関数の返値に対しては使えないので、!== NULLで判定
// 入力値を表示には使わないので、デフォルトでフィルタするのみ。以下を参照
// http://www.php.net/manual/ja/function.filter-input.php
// http://www.php.net/manual/ja/filter.filters.sanitize.php
if ((filter_input(INPUT_POST,'Digits')) !== NULL)
{
    $input = filter_input(INPUT_POST,'Digits');
    
    // DTMF入力判定
    // http://blog.twilio.kddi-web.com/2013/08/01/ゼロからはじめるぜ-twilio-第6回/
    switch ($input) {
    case '1':   // 1の場合はオペレータに接続

        $response->say('オペレータにおつなぎします。しばらくお待ちください。',
                         array('language' => 'ja-jp'));
        // 'twilio MiniCC'Queueに入れる
        // 待ちが発生した場合は、wait.php呼び出し
        // https://jp.twilio.com/docs/api/twiml/enqueue
        // http://blog.twilio.kddi-web.com/2013/07/10/twilio-queue/
        $response->enqueue('twilio MiniCC', array('waitUrl' => 'wait.php', 'method' => 'POST'));
        break;
    
    case '2':   // 2の場合は情報を提供する

        // XMLファイル呼び出し
        $response->redirect('information.xml');
        break;
    
    default:    // 1,2以外の場合は再入力
        
        $gather = $response->gather(array('numDigits' => 1, 'timeout' => '10', 'method' => 'POST'));
        $gather->say('再度入力をお願いします。'
                     . 'お問い合わせは1を、最新の製品情報をお聞きになりたい場合は2を押してください。',
                     array('language' => 'ja-jp'));

        // タイムアウトとなった場合はオペレータに接続
        $response->say('入力が確認できませんでした。オペレータにおつなぎします。',
                 array('language' => 'ja-jp'));
        $response->enqueue('twilio MiniCC', array('waitUrl' => 'wait.php', 'method' => 'POST'));

        break ;
        
    }   
}
// 初回アクセス時処理
else{   
    
    // 発信電話番号を取得し、DB照会
    $dbobject = new Database();

    // POSTで送られてくる発信者番号（ANI）をサニタイジング
    $telnumber = $dbobject->sanitizeString(filter_input(INPUT_POST,'From'));
    // SQL文作成
    $query = "SELECT telnum FROM operators WHERE telnum = '$telnumber'";
    // クエリー発行。検索してマッチした件数が$resultに渡される
    $result = $dbobject->queryRowsMysql($query);
    
    // 発信者がオペレータだった場合
    if ($result){
        // キューに入れる＝受電開始
        // https://jp.twilio.com/docs/api/twiml/queue
        // http://blog.twilio.kddi-web.com/2013/07/10/twilio-queue/
        $dial = $response->dial();
        $dial->queue("twilio MiniCC");
    }
    // 発信者がオペレータ以外＝お客様の場合
    else
    {
        // IVRの提供。電話入力（DTMF入力）値を収集
        $gather = $response->gather(array('numDigits' => 1, 'timeout' => '10', 'method' => 'POST'));
        $gather->say('こちらは、サンプルコールセンタです。'
                     . 'お問い合わせは1を、最新の製品情報をお聞きになりたい場合は2を押してください。',
                    array('language' => 'ja-jp'));

        // タイムアウトとなった場合はオペレータに接続
        $response->say('入力が確認できませんでした。オペレータにおつなぎします。',
                         array('language' => 'ja-jp'));
        $response->enqueue('twilio MiniCC', array('waitUrl' => 'wait.php', 'method' => 'POST'));

    }

}

// TwiML作成
print $response;
