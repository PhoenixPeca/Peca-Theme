<?php
header('Content-Type: text/xml');
define('DS', DIRECTORY_SEPARATOR);
require(__DIR__ . DS . 'kirby' . DS . 'bootstrap.php');
$kirby = kirby();
$site = $kirby->site();
function siteurl() {
return preg_replace('~/~', '//', preg_replace('~/+~', '/', implode('', explode(basename(__FILE__), kirby()->site()->url())).'/'), 1);
}
?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
    <ShortName><?php echo $site->title(); ?></ShortName>
    <Description><?php echo $site->description() ?></Description>
    <Tags><?php echo preg_replace('!\s+!', ' ', preg_replace('/\s*,+\s*/', ', ', $site->keywords())) ?></Tags>
    <Contact><?php echo $site->email() ?></Contact>
    <Url type="text/html" template="<?php echo siteurl(); ?>?q={searchTerms}"/>
    <Url type="application/opensearchdescription+xml" rel="self" template="<?php echo siteurl().'opensearch.php' ?>"/>
    <LongName><?php echo $site->title().' Search' ?></LongName>
    <Image type="image/png"><?php echo siteurl().'assets/images/logo.png' ?></Image>
    <Image type="image/x-icon"><?php echo siteurl().'favicon.ico' ?></Image>
    <Query role="example" searchTerms="outdoor" />
    <Developer><?php echo $site->title().' Development Team' ?></Developer>
    <Attribution><?php echo 'Copyright '.date('Y').' '.$site->title() ?></Attribution>
    <SyndicationRight>open</SyndicationRight>
    <AdultContent><?php if($site->adult() != 'true' && $site->adult() != 'True' && $site->adult() != 'TRUE' && $site->adult() != 'yes' && $site->adult() != 'Yes' && $site->adult() != 'YES'){ echo 'false'; } else { echo 'true'; } ?></AdultContent>
    <Language>*</Language>
    <OutputEncoding>UTF-8</OutputEncoding>
    <InputEncoding>UTF-8</InputEncoding>
</OpenSearchDescription>