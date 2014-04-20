<?php
/**
 * Database Class(Create PDO Object)
 * データベース接続のためのPDOオブジェクトを作成
 *
 * @author rutoru
 * @package Twilio-MiniCC
 * @GitHub  https://github.com/rutoru/Twilio-MiniCC
 */
class Database {

    /**
     * Constants for Enqueue Action
    */
    const DBHOST = "localhost";
    const DBNAME = "";
    const DBUSER = "";
    const DBPASS = "";    
    
    /**
     * Object Variables
    */
    private $pdo;

    /**
     * Constructor (Create POD Object)
     * 
    */
    public function __construct()
    {
        
        // Create PDO Object
        $this->pdo = 
                new PDO(
                    "mysql:dbname=".self::DBNAME.";host=".self::DBHOST.";charset=utf8",
                    self::DBUSER,
                    self::DBPASS,
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                    )
                );
    }
    
    /**
     * Getter - Return PDO Object
     * 
     * @return PDO Object
     * 
    */
    public function getPdo()
    {
    
        return $this->pdo;
        
    }

}