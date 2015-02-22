<?php
$delay = '5';
error_reporting(0);
$status=$_SERVER['REDIRECT_STATUS'];
$codes=array(
      400 => array('Bad Request', 'Your browser sent a request that this server could not understand. You will be redirected to homepage in '.$delay.' seconds.'),
      401 => array('Authorization Required', 'This server could not verify that you are authorized to access the directory or document you requested. You will be redirected to homepage in '.$delay.' seconds.'),
      403 => array('Forbidden', 'You don\'t have enough permissions to access the directory or document you requested. You will be redirected to homepage in '.$delay.' seconds.'),
      404 => array('Not Found', 'The directory of document you requested was not found. You will be redirected to homepage in '.$delay.' seconds.'),
      500 => array('Internal Server Error', 'The server encountered an internal error or misconfiguration and was unable to complete your request. You will be redirected to homepage in '.$delay.' seconds.'),
);

$errortitle=$codes[$status][0];
$message=$codes[$status][1];

if($errortitle==false){
       $errortitle = 'Unknown Error';
       $message = 'An unknown error has occurred. You will be redirected to homepage in '.$delay.' seconds.';
}
function siteURL() {
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'].'/';
return $protocol.$domainName;
}
?>
<html>
<head>
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Courgette">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Coming+Soon">
<link rel="stylesheet" href="<?php echo siteURL(); ?>assets/css/main.css">
<meta http-equiv="refresh" content="<?php echo $delay;?>;url=\">
<title><?php echo $errortitle;?></title>
</head>
<body>
<?php
echo '<h1>'.$errortitle.'</h1>';
echo '<center><p>'.$message.'</p></center>';
?>
</body>
</html>