<?php
// 設定クラス
require_once 'Conf.php';

header('Content-type: text/xml');
 
// put a phone number you've verified with Twilio to use as a caller ID number
//$callerId = "+815031596904";
$callerId = "rutoru";
 
// put your default Twilio Client name here, for when a phone number isn't given
$number   = "rutoru";
 
// get the phone number from the page request parameters, if given
if (isset($_REQUEST['PhoneNumber'])) {
    $number = htmlspecialchars($_REQUEST['PhoneNumber']);
}
 
// wrap the phone number or client name in the appropriate TwiML verb
// by checking if the number given has only digits and format symbols
if (preg_match("/^[\d\+\-\(\) ]+$/", $number)) {
    $numberOrClient = "<Number>" . $number . "</Number>";
} elseif ($number = Conf::QUEUE){
    $numberOrClient = "<Queue url='guidance.php'>" . $number . "</Queue>";
} else {
    $numberOrClient = "<Client>" . $number . "</Client>";
}
?>
 
<Response>
    <Dial callerId="<?php echo $callerId ?>">
          <?php echo $numberOrClient ?>
    </Dial>
</Response>