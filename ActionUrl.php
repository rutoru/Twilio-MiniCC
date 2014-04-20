<?php
/**
 * Action URL Class(Create Twilio Action URL)
 * <Enqueue>`タグのaction URLを基本認証付きで作成
 *
 * @author rutoru
 * @package Twilio-MiniCC
 * @GitHub  https://github.com/rutoru/Twilio-MiniCC
 */
class ActionUrl {

    /**
     * Constants for Action URL
    */
    const URI     = "http://";
    const ID      = "";
    const PASS    = "";
    const DOMAIN  = "";
    const FILELOC = “/hoge/“;    
    
    /**
     * Object Variables
    */
    private $url;

    /**
     * Constructor (Action URL)
     * 
    */
    public function __construct()
    {
        
        // Create Enqueue Action URL
        $this->url = self::URI.self::ID.":".self::PASS."@".self::DOMAIN.self::FILELOC;
        
    }
    
    /**
     * Getter - Return Action URL
     * 
     * @param String PHP File Name
     * @return String Action URL
     * 
    */
    public function getUrl($file)
    {
    
        return $this->url.$file;
        
    }

}