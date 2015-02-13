<?php
class Securimage
{
 const SI_IMAGE_JPEG = 1;
 const SI_IMAGE_PNG = 2;
 const SI_IMAGE_GIF = 3;
 const SI_CAPTCHA_STRING = 0;
 const SI_CAPTCHA_MATHEMATIC = 1;
 const SI_CAPTCHA_WORDS = 2;
 const SI_DRIVER_MYSQL = 'mysql';
 const SI_DRIVER_PGSQL = 'pgsql';
 const SI_DRIVER_SQLITE3 = 'sqlite';
 public $image_width = 215;
 public $image_height = 80;
 public $font_ratio;
 public $image_type = self::SI_IMAGE_PNG;
 public $image_bg_color = '#ffffff';
 public $text_color = '#707070';
 public $line_color = '#707070';
 public $noise_color = '#707070';
 public $text_transparency_percentage = 20;
 public $use_transparent_text = true;
 public $code_length = 6;
 public $case_sensitive = false;
 public $charset = 'ABCDEFGHKLMNPRSTUVWYZabcdefghklmnprstuvwyz23456789';
 public $expiry_time = 900;
 public $session_name = null;
 public $use_wordlist = false;
 public $perturbation = 0.85;
 public $num_lines = 5;
 public $noise_level = 2;
 public $image_signature = '';
 public $signature_color = '#707070';
 public $signature_font;
 public $use_sqlite_db = false;
 public $use_database = false;
 public $skip_table_check = false;
 public $database_driver = self::SI_DRIVER_SQLITE3;
 public $database_host = 'localhost';
 public $database_user = '';
 public $database_pass = '';
 public $database_name = '';
 public $database_table = 'captcha_codes';
 public $database_file;
 public $captcha_type = self::SI_CAPTCHA_STRING;
 public $namespace;
 public $ttf_file;
 public $wordlist_file;
 public $background_directory;
 public $sqlite_database;
 public $audio_path;
 public $audio_use_sox = false;
 public $sox_binary_path = '/usr/bin/sox';
 public $audio_noise_path;
 public $audio_use_noise;
 public $audio_mix_normalization = 0.8;
 public $degrade_audio;
 public $audio_gap_min = 0;
 public $audio_gap_max = 3000;
 protected static $_captchaId = null;
 protected $im;
 protected $tmpimg;
 protected $bgimg;
 protected $iscale = 5;
 public $securimage_path = null;
 protected $code;
 protected $code_display;
 public $display_value;
 protected $captcha_code;
 protected $_timeToSolve = 0;
 protected $no_exit;
 protected $no_session;
 protected $send_headers;
 protected $pdo_conn;
 protected $gdbgcolor;
 protected $gdtextcolor;
 protected $gdlinecolor;
 protected $gdsignaturecolor;
 public
 function __construct($options = array())
 {
  $this->securimage_path = dirname(__FILE__);
  if (is_array($options) && sizeof($options) > 0) {
   foreach($options as $prop => $val) {
    if ($prop == 'captchaId') {
     Securimage::$_captchaId = $val;
     $this->use_database = true;
    }
    else
    if ($prop == 'use_sqlite_db') {
     trigger_error("The use_sqlite_db option is deprecated, use 'use_database' instead", E_USER_NOTICE);
    }
    else {
     $this->$prop = $val;
    }
   }
  }
  $this->image_bg_color = $this->initColor($this->image_bg_color, '#ffffff');
  $this->text_color = $this->initColor($this->text_color, '#616161');
  $this->line_color = $this->initColor($this->line_color, '#616161');
  $this->noise_color = $this->initColor($this->noise_color, '#616161');
  $this->signature_color = $this->initColor($this->signature_color, '#616161');
  if (is_null($this->ttf_file)) {
   $this->ttf_file = $this->securimage_path . '/AHGBold.ttf';
  }
  $this->signature_font = $this->ttf_file;
  if (is_null($this->wordlist_file)) {
   $this->wordlist_file = $this->securimage_path . '/words/words.txt';
  }
  if (is_null($this->database_file)) {
   $this->database_file = $this->securimage_path . '/database/securimage.sq3';
  }
  if (is_null($this->audio_path)) {
   $this->audio_path = $this->securimage_path . '/audio/en/';
  }
  if (is_null($this->audio_noise_path)) {
   $this->audio_noise_path = $this->securimage_path . '/audio/noise/';
  }
  if (is_null($this->audio_use_noise)) {
   $this->audio_use_noise = true;
  }
  if (is_null($this->degrade_audio)) {
   $this->degrade_audio = true;
  }
  if (is_null($this->code_length) || (int)$this->code_length < 1) {
   $this->code_length = 6;
  }
  if (is_null($this->perturbation) || !is_numeric($this->perturbation)) {
   $this->perturbation = 0.75;
  }
  if (is_null($this->namespace) || !is_string($this->namespace)) {
   $this->namespace = 'default';
  }
  if (is_null($this->no_exit)) {
   $this->no_exit = false;
  }
  if (is_null($this->no_session)) {
   $this->no_session = false;
  }
  if (is_null($this->send_headers)) {
   $this->send_headers = true;
  }
  if ($this->no_session != true) {
   if (session_id() == '' || (function_exists('session_status') && PHP_SESSION_NONE == session_status())) {
    if (!is_null($this->session_name) && trim($this->session_name) != '') {
     session_name(trim($this->session_name));
    }
    session_start();
   }
  }
 }
 public static
 function getPath()
 {
  return dirname(__FILE__);
 }
 public static
 function getCaptchaId($new = true, array $options = array())
 {
  if (is_null($new) || (bool)$new == true) {
   $id = sha1(uniqid($_SERVER['REMOTE_ADDR'], true));
   $opts = array(
    'no_session' => true,
    'use_database' => true
   );
   if (sizeof($options) > 0) $opts = array_merge($options, $opts);
   $si = new self($opts);
   Securimage::$_captchaId = $id;
   $si->createCode();
   return $id;
  }
  else {
   return Securimage::$_captchaId;
  }
 }
 public static
 function checkByCaptchaId($id, $value, array $options = array())
 {
  $opts = array(
   'captchaId' => $id,
   'no_session' => true,
   'use_database' => true
  );
  if (sizeof($options) > 0) $opts = array_merge($options, $opts);
  $si = new self($opts);
  if ($si->openDatabase()) {
   $code = $si->getCodeFromDatabase();
   if (is_array($code)) {
    $si->code = $code['code'];
    $si->code_display = $code['code_disp'];
   }
   if ($si->check($value)) {
    $si->clearCodeFromDatabase();
    return true;
   }
   else {
    return false;
   }
  }
  else {
   return false;
  }
 }
 public
 function show($background_image = '')
 {
  set_error_handler(array(&$this,
   'errorHandler'
  ));
  if ($background_image != '' && is_readable($background_image)) {
   $this->bgimg = $background_image;
  }
  $this->doImage();
 }
 public
 function check($code)
 {
  $this->code_entered = $code;
  $this->validate();
  return $this->correct_code;
 }
 public static
 function getCaptchaHtml($options = array())
 {
  if (!isset($options['securimage_path'])) {
   $docroot = (isset($_SERVER['DOCUMENT_ROOT'])) ? $_SERVER['DOCUMENT_ROOT'] : substr($_SERVER['SCRIPT_FILENAME'], 0, -strlen($_SERVER['SCRIPT_NAME']));
   $docroot = realpath($docroot);
   $sipath = dirname(__FILE__);
   $securimage_path = str_replace($docroot, '', $sipath);
  }
  else {
   $securimage_path = $options['securimage_path'];
  }
  $image_id = (isset($options['image_id'])) ? $options['image_id'] : 'captcha_image';
  $image_alt = (isset($options['image_alt_text'])) ? $options['image_alt_text'] : 'CAPTCHA Image';
  $show_audio_btn = (isset($options['show_audio_button'])) ? (bool)$options['show_audio_button'] : true;
  $show_refresh_btn = (isset($options['show_refresh_button'])) ? (bool)$options['show_refresh_button'] : true;
  $audio_but_bg_col = (isset($options['audio_button_bgcol'])) ? $options['audio_button_bgcol'] : '#ffffff';
  $audio_icon_url = (isset($options['audio_icon_url'])) ? $options['audio_icon_url'] : null;
  $audio_play_url = (isset($options['audio_play_url'])) ? $options['audio_play_url'] : null;
  $audio_swf_url = (isset($options['audio_swf_url'])) ? $options['audio_swf_url'] : null;
  $show_input = (isset($options['show_text_input'])) ? (bool)$options['show_text_input'] : true;
  $refresh_alt = (isset($options['refresh_alt_text'])) ? $options['refresh_alt_text'] : 'Refresh Image';
  $refresh_title = (isset($options['refresh_title_text'])) ? $options['refresh_title_text'] : 'Refresh Image';
  $input_text = (isset($options['input_text'])) ? $options['input_text'] : 'Type the text:';
  $input_id = (isset($options['input_id'])) ? $options['input_id'] : 'captcha_code';
  $input_name = (isset($options['input_name'])) ? $options['input_name'] : $input_id;
  $input_attrs = (isset($options['input_attributes'])) ? $options['input_attributes'] : array();
  $image_attrs = (isset($options['image_attributes'])) ? $options['image_attributes'] : array();
  $error_html = (isset($options['error_html'])) ? $options['error_html'] : null;
  $namespace = (isset($options['namespace'])) ? $options['namespace'] : '';
  $rand = md5(uniqid($_SERVER['REMOTE_PORT'], true));
  $securimage_path = rtrim($securimage_path, '/\\');
  $securimage_path = str_replace('\\', '/', $securimage_path);
  $image_attr = '';
  if (!is_array($image_attrs)) $image_attrs = array();
  if (!isset($image_attrs['align'])) $image_attrs['align'] = 'left';
  $image_attrs['id'] = $image_id;
  $show_path = $securimage_path . '/securimage_show.php?';
  if (!empty($namespace)) {
   $show_path.= sprintf('namespace=%s&', $namespace);
  }
  $image_attrs['src'] = $show_path . $rand;
  $image_attrs['alt'] = $image_alt;
  foreach($image_attrs as $name => $val) {
   $image_attr.= sprintf('%s="%s" ', $name, htmlspecialchars($val));
  }
  $html = sprintf('<img %s/>', $image_attr);
  if ($show_audio_btn) {
   $swf_path = $securimage_path . '/securimage_play.swf';
   $play_path = $securimage_path . '/securimage_play.php';
   $icon_path = $securimage_path . '/images/audio_icon.png';
   if (!empty($audio_icon_url)) {
    $icon_path = $audio_icon_url;
   }
   if (!empty($audio_play_url)) {
    $play_path = $audio_play_url;
   }
   if (!empty($audio_swf_url)) {
    $swf_path = $audio_swf_url;
   }
   $html.= sprintf('<object type="application/x-shockwave-flash" data="%s?bgcol=%s&amp;icon_file=%s&amp;audio_file=%s" height="32" width="32">', htmlspecialchars($swf_path) , urlencode($audio_but_bg_col) , urlencode($icon_path) , urlencode($play_path));
   $html.= sprintf('<param name="movie" value="%s?bgcol=%s&amp;icon_file=%s&amp;audio_file=%s" />', htmlspecialchars($swf_path) , urlencode($audio_but_bg_col) , urlencode($icon_path) , urlencode($play_path));
   $html.= '</object><br />';
  }
  if ($show_refresh_btn) {
   $icon_path = $securimage_path . '/images/refresh.png';
   $img_tag = sprintf('<img height="32" width="32" src="%s" alt="%s" onclick="this.blur()" align="bottom" border="0" />', htmlspecialchars($icon_path) , htmlspecialchars($refresh_alt));
   $html.= sprintf('<a tabindex="-1" style="border: 0" href="#" title="%s" onclick="document.getElementById(\'%s\').src = \'%s\' + Math.random(); this.blur(); return false">%s</a><br />', htmlspecialchars($refresh_title) , $image_id, $show_path, $img_tag);
  }
  $html.= '<div style="clear: both"></div>';
  $html.= sprintf('<label for="%s">%s</label> ', htmlspecialchars($input_id) , htmlspecialchars($input_text));
  if (!empty($error_html)) {
   $html.= $error_html;
  }
  $input_attr = '';
  if (!is_array($input_attrs)) $input_attrs = array();
  $input_attrs['type'] = 'text';
  $input_attrs['name'] = $input_name;
  $input_attrs['id'] = $input_id;
  foreach($input_attrs as $name => $val) {
   $input_attr.= sprintf('%s="%s" ', $name, htmlspecialchars($val));
  }
  $html.= sprintf('<input %s/>', $input_attr);
  return $html;
 }
 public
 function getTimeToSolve()
 {
  return $this->_timeToSolve;
 }
 public
 function setNamespace($namespace)
 {
  $namespace = preg_replace('/[^a-z0-9-_]/i', '', $namespace);
  $namespace = substr($namespace, 0, 64);
  if (!empty($namespace)) {
   $this->namespace = $namespace;
  }
  else {
   $this->namespace = 'default';
  }
 }
 public
 function outputAudioFile()
 {
  set_error_handler(array(&$this,
   'errorHandler'
  ));
  require_once dirname(__FILE__) . '/WavFile.php';
  try {
   $audio = $this->getAudibleCode();
  }
  catch(Exception $ex) {
   if (($fp = @fopen(dirname(__FILE__) . '/si.error_log', 'a+')) !== false) {
    fwrite($fp, date('Y-m-d H:i:s') . ': Securimage audio error "' . $ex->getMessage() . '"' . "\n");
    fclose($fp);
   }
   $audio = $this->audioError();
  }
  if ($this->canSendHeaders() || $this->send_headers == false) {
   if ($this->send_headers) {
    $uniq = md5(uniqid(microtime()));
    header("Content-Disposition: attachment; filename=\"securimage_audio-{$uniq}.wav\"");
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Expires: Sun, 1 Jan 2000 12:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
    header('Content-type: audio/x-wav');
    if (extension_loaded('zlib')) {
     ini_set('zlib.output_compression', true);
    }
    else {
     header('Content-Length: ' . strlen($audio));
    }
   }
   echo $audio;
  }
  else {
   echo '<hr /><strong>' . 'Failed to generate audio file, content has already been ' . 'output.<br />This is most likely due to misconfiguration or ' . 'a PHP error was sent to the browser.</strong>';
  }
  restore_error_handler();
  if (!$this->no_exit) exit;
 }
 public
 function getCode($array = false, $returnExisting = false)
 {
  $code = array();
  $time = 0;
  $disp = 'error';
  if ($returnExisting && strlen($this->code) > 0) {
   if ($array) {
    return array(
     'code' => $this->code,
     'display' => $this->code_display,
     'code_display' => $this->code_display,
     'time' => 0
    );
   }
   else {
    return $this->code;
   }
  }
  if ($this->no_session != true) {
   if (isset($_SESSION['securimage_code_value'][$this->namespace]) && trim($_SESSION['securimage_code_value'][$this->namespace]) != '') {
    if ($this->isCodeExpired($_SESSION['securimage_code_ctime'][$this->namespace]) == false) {
     $code['code'] = $_SESSION['securimage_code_value'][$this->namespace];
     $code['time'] = $_SESSION['securimage_code_ctime'][$this->namespace];
     $code['display'] = $_SESSION['securimage_code_disp'][$this->namespace];
    }
   }
  }
  if (empty($code) && $this->use_database) {
   $this->openDatabase();
   $code = $this->getCodeFromDatabase();
   if (!empty($code)) {
    $code['display'] = $code['code_disp'];
    unset($code['code_disp']);
   }
  }
  else {
  }
  if ($array == true) {
   return $code;
  }
  else {
   return $code['code'];
  }
 }
 protected
 function doImage()
 {
  if (($this->use_transparent_text == true || $this->bgimg != '') && function_exists('imagecreatetruecolor')) {
   $imagecreate = 'imagecreatetruecolor';
  }
  else {
   $imagecreate = 'imagecreate';
  }
  $this->im = $imagecreate($this->image_width, $this->image_height);
  $this->tmpimg = $imagecreate($this->image_width * $this->iscale, $this->image_height * $this->iscale);
  $this->allocateColors();
  imagepalettecopy($this->tmpimg, $this->im);
  $this->setBackground();
  $code = '';
  if ($this->getCaptchaId(false) !== null) {
   if (is_string($this->display_value) && strlen($this->display_value) > 0) {
    $this->code_display = $this->display_value;
    $this->code = ($this->case_sensitive) ? $this->display_value : strtolower($this->display_value);
    $code = $this->code;
   }
   else
   if ($this->openDatabase()) {
    $code = $this->getCodeFromDatabase();
    if (is_array($code)) {
     $this->code = $code['code'];
     $this->code_display = $code['code_disp'];
     $code = $code['code'];
    }
   }
  }
  if ($code == '') {
   $this->createCode();
  }
  if ($this->noise_level > 0) {
   $this->drawNoise();
  }
  $this->drawWord();
  if ($this->perturbation > 0 && is_readable($this->ttf_file)) {
   $this->distortedCopy();
  }
  if ($this->num_lines > 0) {
   $this->drawLines();
  }
  if (trim($this->image_signature) != '') {
   $this->addSignature();
  }
  $this->output();
 }
 protected
 function allocateColors()
 {
  $this->gdbgcolor = imagecolorallocate($this->im, $this->image_bg_color->r, $this->image_bg_color->g, $this->image_bg_color->b);
  $alpha = intval($this->text_transparency_percentage / 100 * 127);
  if ($this->use_transparent_text == true) {
   $this->gdtextcolor = imagecolorallocatealpha($this->im, $this->text_color->r, $this->text_color->g, $this->text_color->b, $alpha);
   $this->gdlinecolor = imagecolorallocatealpha($this->im, $this->line_color->r, $this->line_color->g, $this->line_color->b, $alpha);
   $this->gdnoisecolor = imagecolorallocatealpha($this->im, $this->noise_color->r, $this->noise_color->g, $this->noise_color->b, $alpha);
  }
  else {
   $this->gdtextcolor = imagecolorallocate($this->im, $this->text_color->r, $this->text_color->g, $this->text_color->b);
   $this->gdlinecolor = imagecolorallocate($this->im, $this->line_color->r, $this->line_color->g, $this->line_color->b);
   $this->gdnoisecolor = imagecolorallocate($this->im, $this->noise_color->r, $this->noise_color->g, $this->noise_color->b);
  }
  $this->gdsignaturecolor = imagecolorallocate($this->im, $this->signature_color->r, $this->signature_color->g, $this->signature_color->b);
 }
 protected
 function setBackground()
 {
  imagefilledrectangle($this->im, 0, 0, $this->image_width, $this->image_height, $this->gdbgcolor);
  imagefilledrectangle($this->tmpimg, 0, 0, $this->image_width * $this->iscale, $this->image_height * $this->iscale, $this->gdbgcolor);
  if ($this->bgimg == '') {
   if ($this->background_directory != null && is_dir($this->background_directory) && is_readable($this->background_directory)) {
    $img = $this->getBackgroundFromDirectory();
    if ($img != false) {
     $this->bgimg = $img;
    }
   }
  }
  if ($this->bgimg == '') {
   return;
  }
  $dat = @getimagesize($this->bgimg);
  if ($dat == false) {
   return;
  }
  switch ($dat[2]) {
  case 1:
   $newim = @imagecreatefromgif($this->bgimg);
   break;
  case 2:
   $newim = @imagecreatefromjpeg($this->bgimg);
   break;
  case 3:
   $newim = @imagecreatefrompng($this->bgimg);
   break;
  default:
   return;
  }
  if (!$newim) return;
  imagecopyresized($this->im, $newim, 0, 0, 0, 0, $this->image_width, $this->image_height, imagesx($newim) , imagesy($newim));
 }
 protected
 function getBackgroundFromDirectory()
 {
  $images = array();
  if (($dh = opendir($this->background_directory)) !== false) {
   while (($file = readdir($dh)) !== false) {
    if (preg_match('/(jpg|gif|png)$/i', $file)) $images[] = $file;
   }
   closedir($dh);
   if (sizeof($images) > 0) {
    return rtrim($this->background_directory, '/') . '/' . $images[mt_rand(0, sizeof($images) - 1) ];
   }
  }
  return false;
 }
 public
 function createCode()
 {
  $this->code = false;
  switch ($this->captcha_type) {
  case self::SI_CAPTCHA_MATHEMATIC:
   {do {
     $signs = array(
      '+',
      '-',
      'x'
     );
     $left = mt_rand(1, 10);
     $right = mt_rand(1, 5);
     $sign = $signs[mt_rand(0, 2) ];
     switch ($sign) {
     case 'x':
      $c = $left * $right;
      break;
     case '-':
      $c = $left - $right;
      break;
     default:
      $c = $left + $right;
      break;
     }
    }
    while ($c <= 0);
    $this->code = $c;
    $this->code_display = "$left $sign $right";
    break;
   }
  case self::SI_CAPTCHA_WORDS:
   $words = $this->readCodeFromFile(2);
   $this->code = implode(' ', $words);
   $this->code_display = $this->code;
   break;
  default:
   {
    if ($this->use_wordlist && is_readable($this->wordlist_file)) {
     $this->code = $this->readCodeFromFile();
    }
    if ($this->code == false) {
     $this->code = $this->generateCode($this->code_length);
    }
    $this->code_display = $this->code;
    $this->code = ($this->case_sensitive) ? $this->code : strtolower($this->code);
   }
  }
  $this->saveData();
 }
 protected
 function drawWord()
 {
  $width2 = $this->image_width * $this->iscale;
  $height2 = $this->image_height * $this->iscale;
  $ratio = ($this->font_ratio) ? $this->font_ratio : 0.4;
  if ((float)$ratio < 0.1 || (float)$ratio >= 1) {
   $ratio = 0.4;
  }
  if (!is_readable($this->ttf_file)) {
   imagestring($this->im, 4, 10, ($this->image_height / 2) - 5, 'Failed to load TTF font file!', $this->gdtextcolor);
  }
  else {
   if ($this->perturbation > 0) {
    $font_size = $height2 * $ratio;
    $bb = imageftbbox($font_size, 0, $this->ttf_file, $this->code_display);
    $tx = $bb[4] - $bb[0];
    $ty = $bb[5] - $bb[1];
    $x = floor($width2 / 2 - $tx / 2 - $bb[0]);
    $y = round($height2 / 2 - $ty / 2 - $bb[1]);
    imagettftext($this->tmpimg, $font_size, 0, $x, $y, $this->gdtextcolor, $this->ttf_file, $this->code_display);
   }
   else {
    $font_size = $this->image_height * $ratio;
    $bb = imageftbbox($font_size, 0, $this->ttf_file, $this->code_display);
    $tx = $bb[4] - $bb[0];
    $ty = $bb[5] - $bb[1];
    $x = floor($this->image_width / 2 - $tx / 2 - $bb[0]);
    $y = round($this->image_height / 2 - $ty / 2 - $bb[1]);
    imagettftext($this->im, $font_size, 0, $x, $y, $this->gdtextcolor, $this->ttf_file, $this->code_display);
   }
  }
 }
 protected
 function distortedCopy()
 {
  $numpoles = 3;
  for ($i = 0; $i < $numpoles; ++$i) {
   $px[$i] = mt_rand($this->image_width * 0.2, $this->image_width * 0.8);
   $py[$i] = mt_rand($this->image_height * 0.2, $this->image_height * 0.8);
   $rad[$i] = mt_rand($this->image_height * 0.2, $this->image_height * 0.8);
   $tmp = ((-$this->frand()) * 0.15) - .15;
   $amp[$i] = $this->perturbation * $tmp;
  }
  $bgCol = imagecolorat($this->tmpimg, 0, 0);
  $width2 = $this->iscale * $this->image_width;
  $height2 = $this->iscale * $this->image_height;
  imagepalettecopy($this->im, $this->tmpimg);
  for ($ix = 0; $ix < $this->image_width; ++$ix) {
   for ($iy = 0; $iy < $this->image_height; ++$iy) {
    $x = $ix;
    $y = $iy;
    for ($i = 0; $i < $numpoles; ++$i) {
     $dx = $ix - $px[$i];
     $dy = $iy - $py[$i];
     if ($dx == 0 && $dy == 0) {
      continue;
     }
     $r = sqrt($dx * $dx + $dy * $dy);
     if ($r > $rad[$i]) {
      continue;
     }
     $rscale = $amp[$i] * sin(3.14 * $r / $rad[$i]);
     $x+= $dx * $rscale;
     $y+= $dy * $rscale;
    }
    $c = $bgCol;
    $x*= $this->iscale;
    $y*= $this->iscale;
    if ($x >= 0 && $x < $width2 && $y >= 0 && $y < $height2) {
     $c = imagecolorat($this->tmpimg, $x, $y);
    }
    if ($c != $bgCol) {
     imagesetpixel($this->im, $ix, $iy, $c);
    }
   }
  }
 }
 protected
 function drawLines()
 {
  for ($line = 0; $line < $this->num_lines; ++$line) {
   $x = $this->image_width * (1 + $line) / ($this->num_lines + 1);
   $x+= (0.5 - $this->frand()) * $this->image_width / $this->num_lines;
   $y = mt_rand($this->image_height * 0.1, $this->image_height * 0.9);
   $theta = ($this->frand() - 0.5) * M_PI * 0.7;
   $w = $this->image_width;
   $len = mt_rand($w * 0.4, $w * 0.7);
   $lwid = mt_rand(0, 2);
   $k = $this->frand() * 0.6 + 0.2;
   $k = $k * $k * 0.5;
   $phi = $this->frand() * 6.28;
   $step = 0.5;
   $dx = $step * cos($theta);
   $dy = $step * sin($theta);
   $n = $len / $step;
   $amp = 1.5 * $this->frand() / ($k + 5.0 / $len);
   $x0 = $x - 0.5 * $len * cos($theta);
   $y0 = $y - 0.5 * $len * sin($theta);
   $ldx = round(-$dy * $lwid);
   $ldy = round($dx * $lwid);
   for ($i = 0; $i < $n; ++$i) {
    $x = $x0 + $i * $dx + $amp * $dy * sin($k * $i * $step + $phi);
    $y = $y0 + $i * $dy - $amp * $dx * sin($k * $i * $step + $phi);
    imagefilledrectangle($this->im, $x, $y, $x + $lwid, $y + $lwid, $this->gdlinecolor);
   }
  }
 }
 protected
 function drawNoise()
 {
  if ($this->noise_level > 10) {
   $noise_level = 10;
  }
  else {
   $noise_level = $this->noise_level;
  }
  $t0 = microtime(true);
  $noise_level*= 125;
  $points = $this->image_width * $this->image_height * $this->iscale;
  $height = $this->image_height * $this->iscale;
  $width = $this->image_width * $this->iscale;
  for ($i = 0; $i < $noise_level; ++$i) {
   $x = mt_rand(10, $width);
   $y = mt_rand(10, $height);
   $size = mt_rand(7, 10);
   if ($x - $size <= 0 && $y - $size <= 0) continue;
   imagefilledarc($this->tmpimg, $x, $y, $size, $size, 0, 360, $this->gdnoisecolor, IMG_ARC_PIE);
  }
  $t1 = microtime(true);
  $t = $t1 - $t0;
 }
 protected
 function addSignature()
 {
  $bbox = imagettfbbox(10, 0, $this->signature_font, $this->image_signature);
  $textlen = $bbox[2] - $bbox[0];
  $x = $this->image_width - $textlen - 5;
  $y = $this->image_height - 3;
  imagettftext($this->im, 10, 0, $x, $y, $this->gdsignaturecolor, $this->signature_font, $this->image_signature);
 }
 protected
 function output()
 {
  if ($this->canSendHeaders() || $this->send_headers == false) {
   if ($this->send_headers) {
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
   }
   switch ($this->image_type) {
   case self::SI_IMAGE_JPEG:
    if ($this->send_headers) header("Content-Type: image/jpeg");
    imagejpeg($this->im, null, 90);
    break;
   case self::SI_IMAGE_GIF:
    if ($this->send_headers) header("Content-Type: image/gif");
    imagegif($this->im);
    break;
   default:
    if ($this->send_headers) header("Content-Type: image/png");
    imagepng($this->im);
    break;
   }
  }
  else {
   echo '<hr /><strong>' . 'Failed to generate captcha image, content has already been ' . 'output.<br />This is most likely due to misconfiguration or ' . 'a PHP error was sent to the browser.</strong>';
  }
  imagedestroy($this->im);
  restore_error_handler();
  if (!$this->no_exit) exit;
 }
 protected
 function getAudibleCode()
 {
  $letters = array();
  $code = $this->getCode(true, true);
  if (empty($code) || $code['code'] == '') {
   if (strlen($this->display_value) > 0) {
    $code = array(
     'code' => $this->display_value,
     'display' => $this->display_value
    );
   }
   else {
    $this->createCode();
    $code = $this->getCode(true);
   }
  }
  if (empty($code)) {
   $error = 'Failed to get audible code (are database settings correct?).  Check the error log for details';
   trigger_error($error, E_USER_WARNING);
   throw new Exception($error);
  }
  if (preg_match('/(\d+) (\+|-|x) (\d+)/i', $code['display'], $eq)) {
   $math = true;
   $left = $eq[1];
   $sign = str_replace(array(
    '+',
    '-',
    'x'
   ) , array(
    'plus',
    'minus',
    'times'
   ) , $eq[2]);
   $right = $eq[3];
   $letters = array(
    $left,
    $sign,
    $right
   );
  }
  else {
   $math = false;
   $length = strlen($code['display']);
   for ($i = 0; $i < $length; ++$i) {
    $letter = $code['display'] {
     $i
    };
    $letters[] = $letter;
   }
  }
  try {
   return $this->generateWAV($letters);
  }
  catch(Exception $ex) {
   throw $ex;
  }
 }
 protected
 function readCodeFromFile($numWords = 1)
 {
  $fp = fopen($this->wordlist_file, 'rb');
  if (!$fp) return false;
  $fsize = filesize($this->wordlist_file);
  if ($fsize < 128) return false;
  if ((int)$numWords < 1 || (int)$numWords > 5) $numWords = 1;
  $words = array();
  $i = 0;
  do {
   fseek($fp, mt_rand(0, $fsize - 64) , SEEK_SET);
   $data = fread($fp, 64);
   $data = preg_replace("/\r?\n/", "\n", $data);
   $start = @strpos($data, "\n", mt_rand(0, 56)) + 1;
   $end = @strpos($data, "\n", $start);
   if ($start === false) {
    continue;
   }
   else
   if ($end === false) {
    $end = strlen($data);
   }
   $word = strtolower(substr($data, $start, $end - $start));
   $words[] = $word;
  }
  while (++$i < $numWords);
  fclose($fp);
  if ($numWords < 2) {
   return $words[0];
  }
  else {
   return $words;
  }
 }
 protected
 function generateCode()
 {
  $code = '';
  if (function_exists('mb_strlen')) {
   for ($i = 1, $cslen = mb_strlen($this->charset); $i <= $this->code_length; ++$i) {
    $code.= mb_substr($this->charset, mt_rand(0, $cslen - 1) , 1, 'UTF-8');
   }
  }
  else {
   for ($i = 1, $cslen = strlen($this->charset); $i <= $this->code_length; ++$i) {
    $code.= substr($this->charset, mt_rand(0, $cslen - 1) , 1);
   }
  }
  return $code;
 }
 protected
 function validate()
 {
  if (!is_string($this->code) || strlen($this->code) == 0) {
   $code = $this->getCode(true);
  }
  else {
   $code = $this->code;
  }
  if (is_array($code)) {
   if (!empty($code)) {
    $ctime = $code['time'];
    $code = $code['code'];
    $this->_timeToSolve = time() - $ctime;
   }
   else {
    $code = '';
   }
  }
  if ($this->case_sensitive == false && preg_match('/[A-Z]/', $code)) {
   $this->case_sensitive = true;
  }
  $code_entered = trim((($this->case_sensitive) ? $this->code_entered : strtolower($this->code_entered)));
  $this->correct_code = false;
  if ($code != '') {
   if (strpos($code, ' ') !== false) {
    $code_entered = preg_replace('/\s+/', ' ', $code_entered);
    $code_entered = strtolower($code_entered);
   }
   if ((string)$code === (string)$code_entered) {
    $this->correct_code = true;
    if ($this->no_session != true) {
     $_SESSION['securimage_code_disp'][$this->namespace] = '';
     $_SESSION['securimage_code_value'][$this->namespace] = '';
     $_SESSION['securimage_code_ctime'][$this->namespace] = '';
    }
    $this->clearCodeFromDatabase();
   }
  }
 }
 protected
 function saveData()
 {
  if ($this->no_session != true) {
   if (isset($_SESSION['securimage_code_value']) && is_scalar($_SESSION['securimage_code_value'])) {
    unset($_SESSION['securimage_code_value']);
    unset($_SESSION['securimage_code_ctime']);
   }
   $_SESSION['securimage_code_disp'][$this->namespace] = $this->code_display;
   $_SESSION['securimage_code_value'][$this->namespace] = $this->code;
   $_SESSION['securimage_code_ctime'][$this->namespace] = time();
  }
  if ($this->use_database) {
   $this->saveCodeToDatabase();
  }
 }
 protected
 function saveCodeToDatabase()
 {
  $success = false;
  $this->openDatabase();
  if ($this->use_database && $this->pdo_conn) {
   $id = $this->getCaptchaId(false);
   $ip = $_SERVER['REMOTE_ADDR'];
   if (empty($id)) {
    $id = $ip;
   }
   $time = time();
   $code = $this->code;
   $code_disp = $this->code_display;
   $this->clearCodeFromDatabase();
   $query = "INSERT INTO {$this->database_table} (" . "id, code, code_display, namespace, created) " . "VALUES(?, ?, ?, ?, ?)";
   $stmt = $this->pdo_conn->prepare($query);
   $success = $stmt->execute(array(
    $id,
    $code,
    $code_disp,
    $this->namespace,
    $time
   ));
   if (!$success) {
    $err = $stmt->errorInfo();
    $error = "Failed to insert code into database. {$err[1]}: {$err[2]}.";
    if ($this->database_driver == self::SI_DRIVER_SQLITE3) {
     $err14 = ($err[1] == 14);
     if ($err14) $error.= sprintf(" Ensure database directory and file are writeable by user '%s' (%d).", get_current_user() , getmyuid());
    }
    trigger_error($error, E_USER_WARNING);
   }
  }
  return $success !== false;
 }
 protected
 function openDatabase()
 {
  $this->pdo_conn = false;
  if ($this->use_database) {
   $pdo_extension = 'PDO_' . strtoupper($this->database_driver);
   if (!extension_loaded($pdo_extension)) {
    trigger_error("Database support is turned on in Securimage, but the chosen extension $pdo_extension is not loaded in PHP.", E_USER_WARNING);
    return false;
   }
  }
  if ($this->database_driver == self::SI_DRIVER_SQLITE3) {
   if (!file_exists($this->database_file)) {
    $fp = fopen($this->database_file, 'w+');
    if (!$fp) {
     $err = error_get_last();
     trigger_error("Securimage failed to create SQLite3 database file '{$this->database_file}'. Reason: {$err['message']}", E_USER_WARNING);
     return false;
    }
    fclose($fp);
    chmod($this->database_file, 0666);
   }
   else
   if (!is_writeable($this->database_file)) {
    trigger_error("Securimage does not have read/write access to database file '{$this->database_file}. Make sure permissions are 0666 and writeable by user '" . get_current_user() . "'", E_USER_WARNING);
    return false;
   }
  }
  try {
   $dsn = $this->getDsn();
   $options = array();
   $this->pdo_conn = new PDO($dsn, $this->database_user, $this->database_pass, $options);
  }
  catch(PDOException $pdoex) {
   trigger_error("Database connection failed: " . $pdoex->getMessage() , E_USER_WARNING);
   return false;
  }
  catch(Exception $ex) {
   trigger_error($ex->getMessage() , E_USER_WARNING);
   return false;
  }
  try {
   if (!$this->skip_table_check && !$this->checkTablesExist()) {
    $this->createDatabaseTables();
   }
  }
  catch(Exception $ex) {
   trigger_error($ex->getMessage() , E_USER_WARNING);
   $this->pdo_conn = null;
   return false;
  }
  if (mt_rand(0, 100) / 100.0 == 1.0) {
   $this->purgeOldCodesFromDatabase();
  }
  return $this->pdo_conn;
 }
 protected
 function getDsn()
 {
  $dsn = sprintf('%s:', $this->database_driver);
  switch ($this->database_driver) {
  case self::SI_DRIVER_SQLITE3:
   $dsn.= $this->database_file;
   break;
  case self::SI_DRIVER_MYSQL:
  case self::SI_DRIVER_PGSQL:
   if (empty($this->database_host)) {
    throw new Exception('Securimage::database_host is not set');
   }
   else
   if (empty($this->database_name)) {
    throw new Exception('Securimage::database_name is not set');
   }
   $dsn.= sprintf('host=%s;dbname=%s', $this->database_host, $this->database_name);
   break;
  }
  return $dsn;
 }
 protected
 function checkTablesExist()
 {
  $table = $this->pdo_conn->quote($this->database_table);
  switch ($this->database_driver) {
  case self::SI_DRIVER_SQLITE3:
   $query = "SELECT COUNT(id) FROM $table";
   break;
  case self::SI_DRIVER_MYSQL:
   $query = "SHOW TABLES LIKE $table";
   break;
  case self::SI_DRIVER_PGSQL:
   $query = "SELECT * FROM information_schema.columns WHERE table_name = $table;";
   break;
  }
  $result = $this->pdo_conn->query($query);
  if (!$result) {
   $err = $this->pdo_conn->errorInfo();
   if ($this->database_driver == self::SI_DRIVER_SQLITE3 && $err[1] === 1 && strpos($err[2], 'no such table') !== false) {
    return false;
   }
   throw new Exception("Failed to check tables: {$err[0]} - {$err[1]}: {$err[2]}");
  }
  else
  if ($this->database_driver == self::SI_DRIVER_SQLITE3) {
   return true;
  }
  else
  if ($result->rowCount() == 0) {
   return false;
  }
  else {
   return true;
  }
 }
 protected
 function createDatabaseTables()
 {
  $queries = array();
  switch ($this->database_driver) {
  case self::SI_DRIVER_SQLITE3:
   $queries[] = "CREATE TABLE \"{$this->database_table
  }
  \" (
                                id VARCHAR(40),
                                namespace VARCHAR(32) NOT NULL,
                                code VARCHAR(32) NOT NULL,
                                code_display VARCHAR(32) NOT NULL,
                                created INTEGER NOT NULL,
                                PRIMARY KEY(id, namespace)
                              )";
  $queries[] = "CREATE INDEX ndx_created ON {$this->database_table
 }
  (created)";
 break;
case self::SI_DRIVER_MYSQL:
 $queries[] = "CREATE TABLE `{$this->database_table
}
` (
                                `id` VARCHAR(40) NOT NULL,
                                `namespace` VARCHAR(32) NOT NULL,
                                `code` VARCHAR(32) NOT NULL,
                                `code_display` VARCHAR(32) NOT NULL,
                                `created` INT NOT NULL,
                                PRIMARY KEY(id, namespace),
                                INDEX(created)
                              )";
break;
case self::SI_DRIVER_PGSQL:
 $queries[] = "CREATE TABLE {$this->database_table
}
 (
                                id character varying(40) NOT NULL,
                                namespace character varying(32) NOT NULL,
                                code character varying(32) NOT NULL,
                                code_display character varying(32) NOT NULL,
                                created integer NOT NULL,
                                CONSTRAINT pkey_id_namespace PRIMARY KEY (id, namespace)
                              )";
$queries[] = "CREATE INDEX ndx_created ON {$this->database_table
}
 (created);";
break;
}
$this->pdo_conn->beginTransaction();
foreach($queries as $query) {
 $result = $this->pdo_conn->query($query);
 if (!$result) {
  $err = $this->pdo_conn->errorInfo();
  trigger_error("Failed to create table.  {$err[1]}: {$err[2]}", E_USER_WARNING);
  $this->pdo_conn->rollBack();
  $this->pdo_conn = false;
  return false;
 }
}
$this->pdo_conn->commit();
return true;
}
protected
function getCodeFromDatabase()
{
 $code = '';
 if ($this->use_database == true && $this->pdo_conn) {
  if (Securimage::$_captchaId !== null) {
   $query = "SELECT * FROM {$this->database_table} WHERE id = ?";
   $stmt = $this->pdo_conn->prepare($query);
   $result = $stmt->execute(array(
    Securimage::$_captchaId
   ));
  }
  else {
   $ip = $_SERVER['REMOTE_ADDR'];
   $ns = $this->namespace;
   $query = "SELECT * FROM {$this->database_table} WHERE id = ? AND namespace = ?";
   $stmt = $this->pdo_conn->prepare($query);
   $result = $stmt->execute(array(
    $ip,
    $ns
   ));
  }
  if (!$result) {
   $err = $this->pdo_conn->errorInfo();
   trigger_error("Failed to select code from database.  {$err[0]}: {$err[1]}", E_USER_WARNING);
  }
  else {
   if (($row = $stmt->fetch()) !== false) {
    if (false == $this->isCodeExpired($row['created'])) {
     $code = array(
      'code' => $row['code'],
      'code_disp' => $row['code_display'],
      'time' => $row['created'],
     );
    }
   }
  }
 }
 return $code;
}
protected
function clearCodeFromDatabase()
{
 if ($this->pdo_conn) {
  $ip = $_SERVER['REMOTE_ADDR'];
  $ns = $this->pdo_conn->quote($this->namespace);
  $id = Securimage::$_captchaId;
  if (empty($id)) {
   $id = $ip;
  }
  $id = $this->pdo_conn->quote($id);
  $query = sprintf("DELETE FROM %s WHERE id = %s AND namespace = %s", $this->database_table, $id, $ns);
  $result = $this->pdo_conn->query($query);
  if (!$result) {
   trigger_error("Failed to delete code from database.", E_USER_WARNING);
  }
 }
}
protected
function purgeOldCodesFromDatabase()
{
 if ($this->use_database && $this->pdo_conn) {
  $now = time();
  $limit = (!is_numeric($this->expiry_time) || $this->expiry_time < 1) ? 86400 : $this->expiry_time;
  $query = sprintf("DELETE FROM %s WHERE %s - created > %s", $this->database_table, $this->pdo_conn->quote($now, PDO::PARAM_INT) , $this->pdo_conn->quote($limit, PDO::PARAM_INT));
  $result = $this->pdo_conn->query($query);
 }
}
protected
function isCodeExpired($creation_time)
{
 $expired = true;
 if (!is_numeric($this->expiry_time) || $this->expiry_time < 1) {
  $expired = false;
 }
 else
 if (time() - $creation_time < $this->expiry_time) {
  $expired = false;
 }
 return $expired;
}
protected
function generateWAV($letters)
{
 $wavCaptcha = new WavFile();
 $first = true;
 if ($this->audio_use_sox && !is_executable($this->sox_binary_path)) {
  throw new Exception("Path to SoX binary is incorrect or not executable");
 }
 foreach($letters as $letter) {
  $letter = strtoupper($letter);
  try {
   $letter_file = realpath($this->audio_path) . DIRECTORY_SEPARATOR . $letter . '.wav';
   if ($this->audio_use_sox) {
    $sox_cmd = sprintf("%s %s -t wav - %s", $this->sox_binary_path, $letter_file, $this->getSoxEffectChain());
    $data = `$sox_cmd`;
    $l = new WavFile();
    $l->setIgnoreChunkSizes(true);
    $l->setWavData($data);
   }
   else {
    $l = new WavFile($letter_file);
   }
   if ($first) {
    $wavCaptcha->setSampleRate($l->getSampleRate())->setBitsPerSample($l->getBitsPerSample())->setNumChannels($l->getNumChannels());
    $first = false;
   }
   $wavCaptcha->appendWav($l);
   if ($this->audio_gap_max > 0 && $this->audio_gap_max > $this->audio_gap_min) {
    $wavCaptcha->insertSilence(mt_rand($this->audio_gap_min, $this->audio_gap_max) / 1000.0);
   }
  }
  catch(Exception $ex) {
   throw new Exception("Error generating audio captcha on letter '$letter': " . $ex->getMessage());
  }
 }
 $filters = array();
 if ($this->audio_use_noise == true) {
  $wavNoise = false;
  $randOffset = 0;
  if (($noiseFile = $this->getRandomNoiseFile()) !== false) {
   try {
    $wavNoise = new WavFile($noiseFile, false);
   }
   catch(Exception $ex) {
    throw $ex;
   }
   $randOffset = 0;
   if ($wavNoise->getNumBlocks() > 2 * $wavCaptcha->getNumBlocks()) {
    $randBlock = mt_rand(0, $wavNoise->getNumBlocks() - $wavCaptcha->getNumBlocks());
    $wavNoise->readWavData($randBlock * $wavNoise->getBlockAlign() , $wavCaptcha->getNumBlocks() * $wavNoise->getBlockAlign());
   }
   else {
    $wavNoise->readWavData();
    $randOffset = mt_rand(0, $wavNoise->getNumBlocks() - 1);
   }
  }
  if ($wavNoise !== false) {
   $mixOpts = array(
    'wav' => $wavNoise,
    'loop' => true,
    'blockOffset' => $randOffset
   );
   $filters[WavFile::FILTER_MIX] = $mixOpts;
   $filters[WavFile::FILTER_NORMALIZE] = $this->audio_mix_normalization;
  }
 }
 if ($this->degrade_audio == true) {
  $filters[WavFile::FILTER_DEGRADE] = mt_rand(95, 98) / 100.0;
 }
 if (!empty($filters)) {
  $wavCaptcha->filter($filters);
 }
 return $wavCaptcha->__toString();
}
public
function getRandomNoiseFile()
{
 $return = false;
 if (($dh = opendir($this->audio_noise_path)) !== false) {
  $list = array();
  while (($file = readdir($dh)) !== false) {
   if ($file == '.' || $file == '..') continue;
   if (strtolower(substr($file, -4)) != '.wav') continue;
   $list[] = $file;
  }
  closedir($dh);
  if (sizeof($list) > 0) {
   $file = $list[array_rand($list, 1) ];
   $return = $this->audio_noise_path . DIRECTORY_SEPARATOR . $file;
   if (!is_readable($return)) $return = false;
  }
 }
 return $return;
}
protected
function getSoxEffectChain($numEffects = 2)
{
 $effectsList = array(
  'bend',
  'chorus',
  'overdrive',
  'pitch',
  'reverb',
  'tempo',
  'tremolo'
 );
 $effects = array_rand($effectsList, $numEffects);
 $outEffects = array();
 if (!is_array($effects)) $effects = array(
  $effects
 );
 foreach($effects as $effect) {
  $effect = $effectsList[$effect];
  switch ($effect) {
  case 'bend':
   $delay = mt_rand(0, 15) / 100.0;
   $cents = mt_rand(-120, 120);
   $dur = mt_rand(75, 400) / 100.0;
   $outEffects[] = "$effect $delay,$cents,$dur";
   break;
  case 'chorus':
   $gainIn = mt_rand(75, 90) / 100.0;
   $gainOut = mt_rand(70, 95) / 100.0;
   $chorStr = "$effect $gainIn $gainOut";
   for ($i = 0; $i < mt_rand(2, 3); ++$i) {
    $delay = mt_rand(20, 100);
    $decay = mt_rand(10, 100) / 100.0;
    $speed = mt_rand(20, 50) / 100.0;
    $depth = mt_rand(150, 250) / 100.0;
    $chorStr.= " $delay $decay $speed $depth -s";
   }
   $outEffects[] = $chorStr;
   break;
  case 'overdrive':
   $gain = mt_rand(5, 25);
   $color = mt_rand(20, 70);
   $outEffects[] = "$effect $gain $color";
   break;
  case 'pitch':
   $cents = mt_rand(-300, 300);
   $outEffects[] = "$effect $cents";
   break;
  case 'reverb':
   $reverberance = mt_rand(20, 80);
   $damping = mt_rand(10, 80);
   $scale = mt_rand(85, 100);
   $depth = mt_rand(90, 100);
   $predelay = mt_rand(0, 5);
   $outEffects[] = "$effect $reverberance $damping $scale $depth $predelay";
   break;
  case 'tempo':
   $factor = mt_rand(65, 135) / 100.0;
   $outEffects[] = "$effect -s $factor";
   break;
  case 'tremolo':
   $hz = mt_rand(10, 30);
   $depth = mt_rand(40, 85);
   $outEffects[] = "$effect $hz $depth";
   break;
  }
 }
 return implode(' ', $outEffects);
}
protected
function getSoxNoiseData($duration, $numChannels, $sampleRate, $bitRate)
{
 $shapes = array(
  'sine',
  'square',
  'triangle',
  'sawtooth',
  'trapezium'
 );
 $steps = array(
  ':',
  '+',
  '/',
  '-'
 );
 $selShapes = array_rand($shapes, 2);
 $selSteps = array_rand($steps, 2);
 $sweep0 = array();
 $sweep0[0] = mt_rand(100, 700);
 $sweep0[1] = mt_rand(1500, 2500);
 $sweep1 = array();
 $sweep1[0] = mt_rand(500, 1000);
 $sweep1[1] = mt_rand(1200, 2000);
 if (mt_rand(0, 10) % 2 == 0) $sweep0 = array_reverse($sweep0);
 if (mt_rand(0, 10) % 2 == 0) $sweep1 = array_reverse($sweep1);
 $cmd = sprintf("%s -c %d -r %d -b %d -n -t wav - synth noise create vol 0.3 synth %.2f %s mix %d%s%d vol 0.3 synth %.2f %s fmod %d%s%d vol 0.3", $this->sox_binary_path, $numChannels, $sampleRate, $bitRate, $duration, $shapes[$selShapes[0]], $sweep0[0], $steps[$selSteps[0]], $sweep0[1], $duration, $shapes[$selShapes[1]], $sweep1[0], $steps[$selSteps[1]], $sweep1[1]);
 $data = `$cmd`;
 return $data;
}
protected
function audioError()
{
 return @file_get_contents(dirname(__FILE__) . '/audio/en/error.wav');
}
protected
function canSendHeaders()
{
 if (headers_sent()) {
  return false;
 }
 else
 if (strlen((string)ob_get_contents()) > 0) {
  return false;
 }
 return true;
}
function frand()
{
 return 0.0001 * mt_rand(0, 9999);
}
protected
function initColor($color, $default)
{
 if ($color == null) {
  return new Securimage_Color($default);
 }
 else
 if (is_string($color)) {
  try {
   return new Securimage_Color($color);
  }
  catch(Exception $e) {
   return new Securimage_Color($default);
  }
 }
 else
 if (is_array($color) && sizeof($color) == 3) {
  return new Securimage_Color($color[0], $color[1], $color[2]);
 }
 else {
  return new Securimage_Color($default);
 }
}
public
function errorHandler($errno, $errstr, $errfile = '', $errline = 0, $errcontext = array())
{
 $level = error_reporting();
 if ($level == 0 || ($level & $errno) == 0) {
  return true;
 }
 return false;
}
}
class Securimage_Color
{
 public $r;
 public $g;
 public $b;
 public
 function __construct($color = '#ffffff')
 {
  $args = func_get_args();
  if (sizeof($args) == 0) {
   $this->r = 255;
   $this->g = 255;
   $this->b = 255;
  }
  else
  if (sizeof($args) == 1) {
   if (substr($color, 0, 1) == '#') {
    $color = substr($color, 1);
   }
   if (strlen($color) != 3 && strlen($color) != 6) {
    throw new InvalidArgumentException('Invalid HTML color code passed to Securimage_Color');
   }
   $this->constructHTML($color);
  }
  else
  if (sizeof($args) == 3) {
   $this->constructRGB($args[0], $args[1], $args[2]);
  }
  else {
   throw new InvalidArgumentException('Securimage_Color constructor expects 0, 1 or 3 arguments; ' . sizeof($args) . ' given');
  }
 }
 protected
 function constructRGB($red, $green, $blue)
 {
  if ($red < 0) $red = 0;
  if ($red > 255) $red = 255;
  if ($green < 0) $green = 0;
  if ($green > 255) $green = 255;
  if ($blue < 0) $blue = 0;
  if ($blue > 255) $blue = 255;
  $this->r = $red;
  $this->g = $green;
  $this->b = $blue;
 }
 protected
 function constructHTML($color)
 {
  if (strlen($color) == 3) {
   $red = str_repeat(substr($color, 0, 1) , 2);
   $green = str_repeat(substr($color, 1, 1) , 2);
   $blue = str_repeat(substr($color, 2, 1) , 2);
  }
  else {
   $red = substr($color, 0, 2);
   $green = substr($color, 2, 2);
   $blue = substr($color, 4, 2);
  }
  $this->r = hexdec($red);
  $this->g = hexdec($green);
  $this->b = hexdec($blue);
 }
}