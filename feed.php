<?php
define('DS', DIRECTORY_SEPARATOR);
require(__DIR__ . DS . 'kirby' . DS . 'bootstrap.php');
header('Content-type: text/xml; charset="utf-8"');
function siteurl() {
return preg_replace('~/~', '//', preg_replace('~/+~', '/', implode('', explode(basename(__FILE__), kirby()->site()->url())).'/'), 1);
}
?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<title><?php echo site()->title() ?> Feed</title>
<link><?php echo siteurl() ?></link>
<generator><?php echo site()->title() ?></generator>
<lastBuildDate><?php echo date('r', time()) ?></lastBuildDate>
<atom:link href="<?php echo url() ?>" rel="self" type="application/rss+xml" />
<?php if(!empty(site()->description())): ?>
<description><?php echo site()->description() ?></description>
<?php endif ?>
<?php foreach(kirby()->site()->pages()->index()->filterBy('template', 'project')->visible()->sortBy(modified, 'desc') as $item): ?>
<item>
<title><?php echo $item->title() ?></title>
<link><?php echo siteurl().$item->uri() ?></link>
<guid><?php echo siteurl().$item->id() ?></guid>
<pubDate><?php echo $item->modified('r') ?></pubDate>
<description><![CDATA[<?php echo $item->description() ?>]]></description>
</item>
<?php endforeach ?>
</channel>
</rss>
