<?php 
define('DS', DIRECTORY_SEPARATOR);
require(__DIR__ . DS . 'kirby' . DS . 'bootstrap.php');
require(__DIR__ . DS . 'site' . DS . 'config/config.php');
$pages = kirby()->site()->pages();
$ignore = array('sitemap', 'error');
function custurlfmt($url){
$prsdurl = parse_url($url, PHP_URL_PATH);
$prsdurldmn = parse_url(preg_replace('~/~', '//', preg_replace('~/+~', '/', implode('', explode(basename(__FILE__), kirby()->site()->url()))), 1));
$procs = $prsdurldmn['scheme'].'://'.$prsdurldmn['host'].preg_replace('/\/+/', '/', $prsdurl, 1);
return preg_replace( '/\//', '//', preg_replace("/\/+/", "/", $procs), 1);
}
function urlfmt($url){
$prsdurl = parse_url($url, PHP_URL_PATH);
$prsdurldmn = parse_url(preg_replace('~/~', '//', preg_replace('~/+~', '/', implode('', explode(basename(__FILE__), kirby()->site()->url()))), 1));
$procs = $prsdurldmn['scheme'].'://'.$prsdurldmn['host'].preg_replace('/\/sitemap.php/', '', $prsdurl, 1);
return preg_replace( '/\//', '//', preg_replace("/\/+/", "/", $procs), 1);
}
// send the right header
header('Content-type: text/xml; charset="utf-8"');
// echo the doctype
echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach($pages->visible()->index() as $p): ?>
<?php if(in_array($p->uri(), $ignore)) continue ?>

	<url>
<?php if(c::get('url')): ?>
		<loc><?php echo custurlfmt(html($p->url())) ?></loc>
<?php else: ?>
		<loc><?php echo urlfmt(html($p->url())) ?></loc>
<?php endif ?>
		<lastmod><?php echo $p->modified('c') ?></lastmod>
		<priority><?php echo ($p->isHomePage()) ? 1 : number_format(0.5/$p->depth(), 1) ?></priority>
	</url>
	<?php endforeach ?>  
</urlset>