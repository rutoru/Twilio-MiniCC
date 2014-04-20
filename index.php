<?php   // index.php
/**
 * ミニコールセンターサービスを提供するTwilio-MiniCCの処理の中心index.php
 * IVR・キューイング・情報提供・ヒストリカルレポート収集機能を提供
 *
 * @author rutoru
 * @package Twilio-MiniCC
 * @GitHub  https://github.com/rutoru/Twilio-MiniCC
 */
// DB接続クラス
require_once 'Database.php';
// Action URLクラス
require_once 'ActionUrl.php';
// 設定クラス
require_once 'Conf.php';
// Twilio Helperライブラリ（index.phpと同じ場所にServicesフォルダが存在する前提）
require_once 'Services/Twilio.php';

// Twimlオブジェクト作成
$response = new Services_Twilio_Twiml();

// EnqueueAction URL作成
$ea = new ActionUrl();
$enqueue_action_url = $ea->getUrl("exit_enqueue.php");

// POSTで電話入力（DTMF入力）値が送られてきているかどうかの判定。初回アクセス時はelseへ
// isset()は関数の返値に対しては使えないので、!== NULLで判定
// 入力値を表示には使わないので、デフォルトでフィルタするのみ。
if ((filter_input(INPUT_POST,'Digits')) !== NULL)
{

    $input = filter_input(INPUT_POST,'Digits');
    
    // DTMF入力判定
    switch ($input) {
    case '1':   // 1の場合はオペレータに接続

        $response->say('オペレータにおつなぎします。しばらくお待ちください。',
                         array('language' => Conf::LANG));
        
        // Queueに入れる
        // 待ちが発生した場合は、waitUrl呼び出し
        $response->enqueue(Conf::QUEUE, array('waitUrl' => 'wait.php',
                                            'action' => $enqueue_action_url,
                                            'method' => Conf::METHOD));
        break;
    
    case '2':   // 2の場合は情報を提供する

        // XMLファイル呼び出し
        $response->redirect('information.xml');
        break;
    
    default:    // 1,2以外の場合は再入力
        
        $gather = $response->gather(array(
                                    'numDigits' => 1,
                                    'timeout' => '10',
                                    'method' => 'POST'
                                    ));
        $gather->say('再度入力をお願いします。'
                    .'お問い合わせは1を、'
                    .'最新の製品情報をお聞きになりたい場合は2を押してください。',
                     array('language' => Conf::LANG));

        // タイムアウトとなった場合はオペレータに接続
        $response->say('入力が確認できませんでした。オペレータにおつなぎします。しばらくお待ちください。',
                        array('language' => Conf::LANG));
        $response->enqueue(Conf::QUEUE, array(
                                        'waitUrl' => 'wait.php',
                                        'action' => $enqueue_action_url,
                                        'method' => Conf::METHOD
                                        ));
        break ;
        
    }   
}
// 初回アクセス時処理
else{   
    
    try{
   
        // 発信電話番号を取得し、DB照会
        $db = new Database();
        $stmt = $db->getPdo()->prepare('SELECT telnum FROM operators WHERE telnum = :ani');
        $stmt->bindValue(':ani', filter_input(INPUT_POST,'From'));
        $stmt->execute();

        // 発信者がオペレータだった場合
        if ($stmt->rowCount()){

            // operator_queue.phpへリダイレクト
            $response->redirect('operator_queue.php');
            
        }
        // 発信者がオペレータ以外＝お客様の場合
        else
        {
            // IVRの提供。電話入力（DTMF入力）値を収集
            $gather = $response->gather(array(
                                        'numDigits' => 1,
                                        'timeout' => '10',
                                        'method' => Conf::METHOD
                                        ));
            $gather->say('こちらは、サンプルコールセンタです。'
                        .'お問い合わせは1を、最新の製品情報をお聞きになりたい場合は2を押してください。',
                          array('language' => Conf::LANG));

            // タイムアウトとなった場合はオペレータに接続
            $response->say('入力が確認できませんでした。オペレータにおつなぎします。しばらくお待ちください。',
                            array('language' => Conf::LANG));
            $response->enqueue(Conf::QUEUE, array(
                                            'waitUrl' => 'wait.php',
                                            'action' => $enqueue_action_url,
                                            'method' => Conf::METHOD
                                            ));
        }
        
    } catch (Exception $e) {

        // デバッグモード時はエラーメッセージ表示
        if(Conf::DEBUG){
            echo $e->getMessage();
        }
        // 運用時はシステムトラブルガイダンスを流す
        else
        {
            $response->say('ただいま、システムトラブルが発生しています。'
                          .'申し訳ありませんが、時間をおいてからおかけ直しください。',
                            array('language' => Conf::LANG));
        }
        
    }
        
}

// TwiML作成
print $response;
