<?php
/**
 * Databaseクラス
 *
 * @author rutoru
 * @package Twilio-MiniCC
 */
class Database {

    /**
     * DB接続用定数
    */
    const DBHOST = "";
    const DBNAME = "";
    const DBUSER = "";
    const DBPASS = "";
    
    /**
     * オブジェクト変数
    */
    private $mysqli;    // mysqliオブジェクトが格納

    /**
     * コンストラクタ（mysqliオブジェクトを作成）
     * 
    */
    public function __construct()
    {
        $this->mysqli = new mysqli(self::DBHOST, self::DBUSER, self::DBPASS, self::DBNAME);
        if ($this->mysqli->connect_errno) {
           echo "Failed to connect to MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
        }
                
    }

    /**
     * デストラクタ（DB接続クローズ）
     * 
    */
    public function __destruct()
    {
        
        $this->mysqli->close();
    
    }

    /**
     * DB接続クローズ
     * 
    */
    public function close()
    {
        $this->mysqli->close();
    }
    
    /**
     * 検索結果行数取得
     * 
     * @param string $var サニタイジング対象文字列
     * @return サイニタイジング後の文字列
    */
    function queryRowsMysql($query)
    {

        $result = $this->mysqli->query($query)->num_rows;
        return $result;
    }

    /**
     * サニタイジング
     *
     * @param string $var filter_input後のサニタイジング対象文字列
     * @return サイニタイジング後の文字列
    */
    function sanitizeString($var)
    {
        // filter_inputされている前提のため、以下コメントアウト
        // $var = strip_tags($var);
        $var = htmlentities($var);
        $var = stripslashes($var);
        return $this->mysqli->real_escape_string($var);
    }
}
