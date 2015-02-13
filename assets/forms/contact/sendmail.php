<?php
error_reporting(0);
require_once "../../../kirby/toolkit/vendors/yaml/yaml.php";
$Data = spyc_load_file('../../../content/site.txt');
$EmailAddress = $Data['Email'];
  //start a session -- needed for Securimage Captcha check
  session_start();
  define("MY_EMAIL", $EmailAddress);
  /**
   * Sets error header and json error message response.
   *
   * @param  String $messsage error message of response
   * @return void
   */
  function errorResponse ($messsage) {
    header('HTTP/1.1 500 Internal Server Error');
    die(json_encode(array('message' => $messsage)));
  }
  /**
   * Return a formatted message body of the form:
   * Name: <name of submitter>
   * IP: <IP of submitter>
   * Date Sent: <Date sent, Time sent>
   * 
   *
   * <message/comment submitted by user>
   *
   * @param String $name     name of submitter
   * @param String $messsage message/comment submitted
   */
  function setMessageBody ($name, $message) {
    $message_body = "Sender's Name: " . $name."\n"."Sender's IP: " . $_SERVER['REMOTE_ADDR']."\n"."Date Sent: " . date('l, jS \of F Y h:i A')."\n\n";
    $message_body .= nl2br($message);
    return $message_body;
  }
  $email = $_POST['email']; 
  $message = $_POST['message'];
  header('Content-type: application/json');
  //do some simple validation. this should have been validated on the client-side also
  if (empty($email) || empty($message)) {
  	errorResponse('Email or message is empty.');
  }
  //do Captcha check, make sure the submitter is not a robot:)...
  include_once 'securimage.php';
  $securimage = new Securimage();
  if (!$securimage->check($_POST['captcha_code'])) {
    errorResponse('Invalid Security Code');
  }
  //try to send the message
  if(mail(MY_EMAIL, $Data['Title']." Support", setMessageBody($_POST["name"], $message), "From: $email")) {
  	echo json_encode(array('message' => 'Your message was successfully submitted.'));
  } else {
  	header('HTTP/1.1 500 Internal Server Error');
  	echo json_encode(array('message' => 'Unexpected error while attempting to send e-mail.'));
  }
?>