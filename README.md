Twilio-MiniCC
======================
[Twilio](http://twilio.kddi-web.com) を使ったミニコールセンターシステムです。

概要
------
### 機能概要 ###
[Twilio](http://twilio.kddi-web.com) を使ったコールセンターシステムで、以下の機能を実装しています。

+ 音声自動応答（IVR）
+ 音声ガイダンス
+ キューイング（待ち順番アナウンス付き）

### 電話をかけるお客様から見た動き ###
お客様がある電話番号に電話をかけると、お問い合わせは1を、最新の製品情報をお聞きになりたい場合は2を押すように求められます。1を押したら、オペレータに接続します。オペレータ不在時はキューに入ります。お客様の待ちの順番をアナウンスした後、保留音が流れます。オペレータが準備でき次第、オペレータに接続します。2を押したら、製品情報ガイダンスが流れます。1,2以外が押されたら再度入力が求められます。10秒待っても何の入力も無い場合、あるいはお客様がPB信号を送ることができない場合は、オペレータにつなぎます。

### 電話を受けるオペレータ（エージェント）から見た動き ###
オペレータは、お客様と同じ電話番号に電話します。発信者番号がDB検索され、オペレータだと判定されれば、オペレータは待ち受け用のキューに入り、お客様の電話を受けることになります。

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
テーブルを作成します。

    CREATE TABLE operators (
        telnum varchar(20),
        PRIMARY KEY (telnum)) ENGINE InnoDB;

オペレータにしたい電話機の電話番号をINSERTしてください。

### Twilio-MiniCC PHPファイル/XMLファイル配置 ###
Twilio-MiniCCのPHPファイル/XMLファイルを配置します。インターネットでアクセス可能である必要があります。

+ index.php
+ Database.php
+ wait.php
+ information.xml

Database.phpの定数部分は使用環境に合わせてください（データベース名等です）。
それぞれのファイルの意味等は実際のソースコードを見てください。参照サイトも含め、コメントも出来る限り入れました。

### セキュリティ設定 ###
TwilioはApacheのBasic認証に対応しています。Twilio-MiniCCを設置するフォルダに、Basic認証設定することをオススメします。

### Twilio設定 ###
[Twilioにログイン](https://jp.twilio.com/login/kddi-web)し、「電話番号」をクリックします。Twilio-MiniCCを起動させたい電話番号をクリックし、「Request URL」を編集します。以下のように記載します。

    http://A:B@C/D/index.php

+ `A`: Basic認証のユーザ名
+ `B`: Basic認証のパスワード
+ `C`: Webサーバのドメイン名
+ `D`: Twilio-MiniCCを置いた場所

以上で終わりです。
   
ライセンス
----------
Copyright &copy; 2014 rutoru
Licensed under [MIT license][MIT].    
https://github.com/rutoru/Twilio-MiniCC/blob/master/LICENSE
 
[MIT]: http://www.opensource.org/licenses/mit-license.php
