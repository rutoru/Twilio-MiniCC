Twilio-MiniCC
======================
[Twilio](http://twilio.kddi-web.com) を使ったミニコールセンターシステムです。
Ver.2でヒストリカルレポート収集機能を実装しました。

概要
------
### 機能概要 ###
[Twilio](http://twilio.kddi-web.com) を使ったコールセンターシステムで、以下の機能を実装しています。Ver.2からヒストリカルレポート収集機能を実装し、プログラムの作りも大幅に変更しています。

+ 音声自動応答（IVR）
+ 音声ガイダンス
+ キューイング（待ち順番アナウンス付き）
+ ヒストリカルレポート収集（Ver.2以降）

### 電話をかけるお客様から見た動き ###
お客様がある電話番号に電話をかけると、お問い合わせは1を、最新の製品情報をお聞きになりたい場合は2を押すように求められます。1を押したら、オペレータに接続します。オペレータ不在時はキューに入ります。お客様の待ちの順番をアナウンスした後、保留音が流れます。オペレータが準備でき次第、オペレータに接続します。2を押したら、製品情報ガイダンスが流れます。1,2以外が押されたら再度入力が求められます。10秒待っても何の入力も無い場合、あるいはお客様がPB信号を送ることができない場合は、オペレータにつなぎます。

### 電話を受けるオペレータ（エージェント）から見た動き ###
オペレータは、お客様と同じ電話番号に電話します。発信者番号がDB検索され、オペレータだと判定されれば、オペレータは待ち受け用のキューに入り、お客様の電話を受けることになります。

### ヒストリカルレポート収集 ###
以下の３カ所でヒストリカルレポートを収集します。
MySQLのテーブルに格納されます。レポートの参照は、MySQLの色々なツールで可能だと思います。Ver.2では、データベース設計が十分ではありません。

+ お客様側キュー（Enqueue）から出た時 … お客様がキューから出る際の情報です。QueueResultを取得できるので、キューに入った後にお客様が電話を切った（放棄呼）の情報を取得することができます。コールセンターで放棄呼がどれだけ発生しているのかを把握することは極めて重要になります。Twilio-MiniCCでは、`exit_enqueue.php`で実装され、データは`enqueue_data`テーブルに格納されます。
+ オペレータ側キュー（Queue）から出た時 … オペレータが応答した際の情報です。`guidance.php`で実装され、データは`queue_data`テーブルに格納します。
+ 通話終了時 ... 「StatusCallback」です。StatusCallbackは通話終了後に非同期で発生するリクエストで、通話に関する情報を収集しデータベースに書き込みを行うことができます。デフォルトはオフ。Twilioの設定画面から「電話番号」をクリックした後、 「Optional Voice Settings」をクリック。表示される「Status Callback URL」に作成したプログラムを設定する必要があります。`status_callback.php` で実装され、データは`statuscallback_data`テーブルに格納されます。

### シーケンス ###
入電からキューに入るまでのシーケンスです。

[![Sequence][image]](https://www.flickr.com/photos/40853659@N06/13815287663)
 
[image]: https://farm3.staticflickr.com/2914/13815287663_1176bee04a.jpg "Sequence"

インストール
------
### PHPとPHP Helperライブラリのインストール ###
PHPで実装されています。PHPが必要です。Twilioからインターネットでアクセス可能である必要があります。Webサーバ等にインストールしてください。バージョンは5.5を使って開発しました。また、TwilioのPHP Helperライブラリを使っていますので、[インストール](https://jp.twilio.com/docs/php/install)が必要です。「ZIPファイルでインストール」を前提にしており、`require_once 'Services/Twilio.php';`とあるように、Twilio-MiniCC本体と同じ場所にライブラリのServiceフォルダ以下が設置される前提です。適宜環境にあわせて変更してください。

### MySQLのインストールとデータベース設定 ###
MySQLでオペレータかどうかの認証を行います。MySQLのインストールを行ってください。開発バージョンは5.1を使いました。

データベース（名前任意）を作成し、ユーザ（名前任意）に権限を付与します。
`twilio_minicc_SQLs.php`を使って、テーブルを作成します。  
オペレータにしたい電話機の電話番号を、operatorsテーブルにINSERTしてください。

### Twilio-MiniCC PHPファイル/XMLファイル配置 ###
Twilio-MiniCCのPHPファイル/XMLファイルを配置します。インターネットでアクセス可能である必要があります。

#### 共通クラス ####
+ `Database.php` ... DB接続でPDOオブジェクトを作成するクラスです。
+ `Conf.php` ... 共通設定をクラス定数にまとめたクラスです。
+ `ActionUrl.php` ... `<Enqueue>`タグのaction URLを基本認証付きで作成します。

#### 個別PHP/XMLファイル ####
+ `index.php` ... Twilio-MiniCCの処理の中心です。
+ `operator_queue.php` … オペレータをキューに入れます。`<Redirect>`タグにより、繰り返し電話を取ります。
+ `wait.php` … お客様がキューイング中にガイダンスを流します。
+ `guidance.php` … オペレータが応答した際に、お客様側にガイダンスを流します。また、そのときの情報を、`queue_data`テーブルに格納します。
+ `exit_enqueue.php` … お客様がキューから出る際の情報を、`enqueue_data`テーブルに格納します。キューに入った後にお客様が電話を切った（放棄呼）場合、`QueueResult`に`hangup`として記録されます。何もレスポンスが無いとオペレータから切断したときエラーメッセージになるため、空のTwiMLを出力します。
+ `status_callback.php` … 通話終了後に情報を、`statuscallback_data`テーブルに格納します。
+ `information.xml` … お客様に音声で情報を提供するXMLファイルです。

共通クラスの定数部分は使用環境に合わせてください（データベース名等）。

### セキュリティ設定 ###
TwilioはApacheのBasic認証に対応しています。Twilio-MiniCCを設置するフォルダに、Basic認証設定することをオススメします。

### Twilio設定 ###
#### index.php ####
[Twilioにログイン](https://jp.twilio.com/login/kddi-web)し、「電話番号」をクリックします。Twilio-MiniCCを起動させたい電話番号をクリックし、「Request URL」を編集します。以下のように記載します。

    http://A:B@C/D/index.php

+ `A`: Basic認証のユーザ名
+ `B`: Basic認証のパスワード
+ `C`: Webサーバのドメイン名
+ `D`: Twilio-MiniCCを置いた場所

#### status_callback.php ####
同じく、「電話番号」をクリックした後、 「Optional Voice Settings」をクリック。表示される「Status Callback URL」に、index.phpと同じ書式で設置します。

    http://A:B@C/D/status_callback.php

以上で終わりです。
   
ライセンス
----------
Copyright &copy; 2014 rutoru
Licensed under [MIT license][MIT].    
https://github.com/rutoru/Twilio-MiniCC/blob/master/LICENSE
 
[MIT]: http://www.opensource.org/licenses/mit-license.php
