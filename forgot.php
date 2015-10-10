<?php
define('DS', DIRECTORY_SEPARATOR);
require(__DIR__ . DS . 'kirby' . DS . 'toolkit' . DS . 'bootstrap.php');
require(__DIR__ . DS . 'kirby' . DS . 'toolkit' . DS . 'vendors' . DS . 'yaml' . DS . 'yaml.php');
$KirbySite = spyc_load_file('content' . DS . 'site.txt');
$filenameGET = 'site'. DS . 'accounts' . DS . $_GET['username'] . '.php';
$filenamePOST = 'site'. DS . 'accounts' . DS . $_POST['username'] . '.php';
$DataPOST = spyc_load_file($filenamePOST);
$DataGET = spyc_load_file($filenameGET);
function TimeCount($val) {
$processed = time() - strtotime($val);
$time = ($processed<1)? '0' : $processed;
return $time;
}
function generateRandomString($length = 10) {
$characters = 'abc1defghij2klmno3pq4rstuvw5xyzABCD6EFGH7IJKLMNO8PQRS9TUVWX0YZ';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < $length; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
return $randomString;
}
?>
<?php
if(!file_exists("kirby/.htresetkey")) {
$fp = fopen("kirby/.htresetkey","w");  
fwrite($fp,generateRandomString(8));  
fclose($fp);
} else {
$checkkey = file_get_contents('kirby/.htresetkey');
if(strlen($checkkey) != 8) {
$fp = fopen("kirby/.htresetkey","w");  
fwrite($fp,generateRandomString(8));  
fclose($fp);
}
}
?>
<?php if(isset($_GET["username"]) && isset($_GET["key"]) && isset($_GET["token"])): ?>
<?php if($_GET['username'] != ''): ?>
<?php if($_GET['key'] != ''): ?>
<?php if($_GET['token'] != ''): ?>
<?php if(file_exists($filenameGET)): ?>
<?php if($DataGET['password'] == crypt::decode(base64_decode($_GET['key']), file_get_contents('kirby/.htresetkey'), 'blowfish')): ?>
<?php if(TimeCount(crypt::decode(base64_decode($_GET['token']), file_get_contents('kirby/.htresetkey'), 'blowfish')) <= 18000 && TimeCount(crypt::decode(base64_decode($_GET['token']), file_get_contents('kirby/.htresetkey'), 'blowfish')) != 0): ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow" />
<base href="<?php echo url::base(); ?>/panel/" />
<title><?php echo $KirbySite['Title'] ?> | Panel | Reset Password</title>
<link rel="stylesheet" href="<?php echo url::base(); ?>/panel/assets/css/panel.css?v=2.1.1">
<!-- custom panel stylesheet -->
</head>
<body class="login grey ltr">
<div class="modal-content">
<!--[if lt IE 10]>
<div class="message message-is-alert">
<span class="message-content">You are using an outdated browser. Please upgrade to the latest version of <a href="http://windows.microsoft.com/internet-explorer">Internet Explorer</a> or switch to <a href="http://mozilla.org/firefox">Firefox</a>, <a href="http://www.apple.com/safari/">Safari</a>, <a href="http://google.com/chrome">Google Chrome</a> or <a href="http://opera.com">Opera</a>.</span>
<span class="message-toggle"><i>&times;</i></span>
</div>
<![endif]-->
<?php
if(array_key_exists('password', $_POST)) {
if(isset($_POST['password'])) {
$modify = yaml::read($filenameGET);
$modify['password'] = password::hash($_POST['password']);
unset($modify[0]);
if(file_exists($filenameGET)) {
if($DataGET['password'] == crypt::decode(base64_decode($_GET['key']), file_get_contents('kirby/.htresetkey'), 'blowfish')) {
file_put_contents($filenameGET, "<?php if(!defined('KIRBY')) exit ?>\n\n".yaml::encode($modify));
echo '<div class="message message-is-notice"><span class="message-content">You may now login with your new password</span><span class="message-toggle"><i>×</i></span></div>';
} else {
echo '<div class="message message-is-alert"><span class="message-content">Invalid key</span><span class="message-toggle"><i>×</i></span></div>';
}
} else {
echo '<div class="message message-is-alert"><span class="message-content">Username does\'nt exist</span><span class="message-toggle"><i>×</i></span></div>';
}
}
}
?>
<form method="post" class="form"><fieldset class="fieldset field-grid cf"><div class="field field-grid-item field-with-icon"><label class="label" for="form-field-password">New Password<abbr title="Required">*</abbr></label><div class="field-content"><input class="input" type="password" required="" name="password" autocomplete="on" id="form-field-password"><div class="field-icon"><i class="icon fa fa-key"></i></div></div></div></fieldset><fieldset class="fieldset buttons cf buttons-centered"><input class="btn btn-rounded btn-submit" type="submit" value="Reset" data-saved="Saved!"></fieldset><input type="hidden" name="_csfr" value="bxoXgW6DCeNOddd4bSYOLhN3Ks8rMvCtXilHVN9XmRn6APtQlt92z7i23aqVYHB5"></form>
</div>
<script src="<?php echo url::base(); ?>/panel/assets/js/panel.js?v=2.1.1"></script>
<script>
$('.message').on('click', function() {
$(this).addClass('hidden');
});
</script>
</body>
</html>
<?php else: ?>
<?php if(TimeCount(crypt::decode(base64_decode($_GET['token']), file_get_contents('kirby/.htresetkey'), 'blowfish')) == 0): ?>
Token is either invalid or expired
<?php else: ?>
Token is either invalid or expired
<?php endif ?>
<?php endif ?>
<?php else: ?>
Key is either invalid or expired
<?php endif ?>
<?php else: ?>
Username must be valid
<?php endif ?>
<?php else: ?>
Token parameter must not be blank
<?php endif ?>
<?php else: ?>
Key parameter must not be blank
<?php endif ?>
<?php else: ?>
Username parameter must not be blank
<?php endif ?>
<?php else: ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow" />
<base href="<?php echo url::base(); ?>/panel/" />
<title><?php echo $KirbySite['Title'] ?> | Panel | Reset Password</title>
<link rel="stylesheet" href="<?php echo url::base(); ?>/panel/assets/css/panel.css?v=2.1.1">
<!-- custom panel stylesheet -->
</head>
<body class="login grey ltr">
<div class="modal-content">
<!--[if lt IE 10]>
<div class="message message-is-alert">
<span class="message-content">You are using an outdated browser. Please upgrade to the latest version of <a href="http://windows.microsoft.com/internet-explorer">Internet Explorer</a> or switch to <a href="http://mozilla.org/firefox">Firefox</a>, <a href="http://www.apple.com/safari/">Safari</a>, <a href="http://google.com/chrome">Google Chrome</a> or <a href="http://opera.com">Opera</a>.</span>
<span class="message-toggle"><i>&times;</i></span>
</div>
<![endif]-->
<?php
function emailValidation($email) {
$regex = '/([a-z0-9_]+|[a-z0-9_]+\.[a-z0-9_]+)@(([a-z0-9]|[a-z0-9]+\.[a-z0-9]+)+\.([a-z]{2,4}))/i';
if(preg_match($regex, $email) === 1) {return true;} else {return false;}
}
if(array_key_exists('username', $_POST)) {
if(isset($_POST['username'])) {
if(file_exists($filenamePOST)) {
if(emailValidation($DataPOST['email']) === true){
$to = $DataPOST['email'];
$subject = $KirbySite['Title']." Reset Password";
$message = '
<html>
<head>
<style>
</style>
</head>
<body>
<b>Hello '.$DataPOST['username'].',</b>
<br>
<p>We recently recieved a password reset request for your email address. If you would like to reset your password, please do so using the following link:<br><a href="'.url::base().'/'.basename(__FILE__).'?username='.$DataPOST['username'].'&key='.urlencode(base64_encode(crypt::encode($DataPOST['password'], file_get_contents('kirby/.htresetkey'), 'blowfish'))).'&token='.urlencode(base64_encode(crypt::encode(date('Y-m-d H:i:s', time()), file_get_contents('kirby/.htresetkey'), 'blowfish'))).'">Reset your password</a></p>
<p>Please note that the link above is only valid for 5 hours, so if you wish to reset your password after this, you\'ll have to request it again using the <a href="'.url::base().'/forgot.php'.'">forgotten password page</a>.</p>
<p>If you did not request a password reset, please ignore this email.</p>
</body>
</html>
';
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: reset@'.((substr(url::host(), 0, 4) == "www.")?preg_replace('/www./', '', url::host(), 1):url::host()) . "\r\n";
mail($to,$subject,$message,$headers);
echo '<div class="message message-is-notice"><span class="message-content">Reset link has been emailed</span><span class="message-toggle"><i>×</i></span></div>';
} else {
echo '<div class="message message-is-alert"><span class="message-content">An error has occured</span><span class="message-toggle"><i>×</i></span></div>';
}
} else {
if($_POST['username']) {
echo '<div class="message message-is-alert"><span class="message-content">Invalid username</span><span class="message-toggle"><i>×</i></span></div>';
}
}
}
}
?>    
<form method="post" class="form"><fieldset class="fieldset field-grid cf"><div class="field field-grid-item field-with-icon"><label class="label" for="form-field-username">Username<abbr title="Required">*</abbr></label><div class="field-content"><input class="input" type="text" required name="username" autocomplete="on" autofocus id="form-field-username"><div class="field-icon"><i class="icon fa fa-user"></i></div></div></div></fieldset><fieldset class="fieldset buttons cf buttons-centered"><input class="btn btn-rounded btn-submit" type="submit" data-saved="Saved!" value="Reset"></fieldset><input type="hidden" name="_csfr" value="gDTBw4vUYzKGkOI9d0Pnco5Xdg81nVY0aa3AFtrMtxu9nwn1p0oLNASFRfLM6dPK"></form>
</div>
<script src="<?php echo url::base(); ?>/panel/assets/js/panel.js?v=2.1.1"></script>
<script>
$('.message').on('click', function() {
$(this).addClass('hidden');
});
</script>
</body>
</html>
<?php endif ?>
