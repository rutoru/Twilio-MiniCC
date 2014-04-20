<?php
/**
 * Configuration Class
 * 設定を一元管理するクラス
 *
 * @author rutoru
 * @package Twilio-MiniCC
 * @GitHub  https://github.com/rutoru/Twilio-MiniCC
 */
class Conf {

    /**
     * Twilio Account Information
    */
    // Set your Twilio API credentials here
    const ACCOUNT_SID   = '';
    const ACCOUNT_TOKEN = '';
    // Set your Twilio Application Sid here
    const APP_SID       = '';
    
    /**
     * Constants
    */
    const DEBUG     = false;
    const QUEUE     = "";
    const METHOD    = "POST";
    const LANG      = "ja-jp";
    const MOH_LONG  = "http://com.twilio.sounds.music.s3.amazonaws.com/MARKOVICHAMP-Borghestral.mp3";
    const MOH_SHORT = "";

}