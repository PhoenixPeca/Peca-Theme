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
define('DS', DIRECTORY_SEPARATOR);
require(__DIR__ . DS . 'kirby' . DS . 'bootstrap.php');
function siteurl() {
return preg_replace('~/~', '//', preg_replace('~/+~', '/', implode('', explode(basename(__FILE__), kirby()->site()->url()))), 1);
}
?>
<html>
<head>
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Courgette">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Coming+Soon">
<link rel="stylesheet" href="<?php echo siteurl(); ?>assets/css/main.css">
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