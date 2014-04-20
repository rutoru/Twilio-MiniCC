/**
 * StatusCallBackデータ格納テーブル
 *
 * @author rutoru
 * @package Twilio-MiniCC
 * @GitHub  https://github.com/rutoru/Twilio-MiniCC
*/
CREATE TABLE statuscallback_data
(
	`CallSid` CHAR(34) PRIMARY KEY NOT NULL UNIQUE,
	`From` VARCHAR(255),
	`To` VARCHAR(255),
	`CallStatus` VARCHAR(15),
	`ApiVersion` CHAR(10),
	`Direction` VARCHAR(15),
	`ForwardedFrom` VARCHAR(255),
	`CallerName` VARCHAR(255),
	`CallDuration` INT,
	`RecordingUrl` VARCHAR(255),
	`RecordingSid` CHAR(34),
        `RecordingDuration` INT,
        `Time` DATETIME,
         INDEX cst_idx(CallStatus),
         INDEX rsid_idx(RecordingSid)
)
ENGINE InnoDB;

/**
 * Queueデータ格納テーブル
 *
 * @author rutoru
 * @package Twilio-MiniCC
 * @GitHub  https://github.com/rutoru/Twilio-MiniCC
*/
CREATE TABLE queue_data
(
	`CallSid` CHAR(34) PRIMARY KEY NOT NULL UNIQUE,
	`From` VARCHAR(255),
	`To` VARCHAR(255),
	`CallStatus` VARCHAR(15),
	`ApiVersion` CHAR(10),
	`Direction` VARCHAR(15),
	`ForwardedFrom` VARCHAR(255),
	`CallerName` VARCHAR(255),
	`QueueSid` CHAR(34),
	`QueueTime` INT,
	`DequeingCallSid` CHAR(34),
        `Time` DATETIME,
         INDEX cst_idx(CallStatus),
         INDEX qu_idx(QueueSid),
         INDEX dqu_idx(DequeingCallSid)
)
ENGINE InnoDB;

/**
 * Enqueueデータ格納テーブル
 *
 * @author rutoru
 * @package Twilio-MiniCC
 * @GitHub  https://github.com/rutoru/Twilio-MiniCC
*/
CREATE TABLE enqueue_data
(
	`CallSid` CHAR(34) PRIMARY KEY NOT NULL UNIQUE,
	`From` VARCHAR(255),
	`To` VARCHAR(255),
	`CallStatus` VARCHAR(15),
	`ApiVersion` CHAR(10),
	`Direction` VARCHAR(15),
	`ForwardedFrom` VARCHAR(255),
	`CallerName` VARCHAR(255),
	`QueueResult` VARCHAR(15),
	`QueueSid` CHAR(34),
	`QueueTime` INT,
        `Time` DATETIME,
         INDEX cst_idx(CallStatus),
         INDEX rqu_idx(QueueResult),
         INDEX qu_idx(QueueSid)
)
ENGINE InnoDB;

/**
 * オペレータテーブル
 *
 * @author rutoru
 * @package Twilio-MiniCC
 * @GitHub  https://github.com/rutoru/Twilio-MiniCC
*/
CREATE TABLE operators
(
	`telnum` varchar(20) PRIMARY KEY NOT NULL UNIQUE
)
ENGINE InnoDB;

