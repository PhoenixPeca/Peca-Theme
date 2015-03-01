<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
<link rel="search" type="application/opensearchdescription+xml" title="<?php echo $site->title()->html() ?>" href="<?php echo url('opensearch.php') ?>" />
<title><?php echo $page->title()->html()?> - <?php echo $site->title()->html() ?></title>
<?php if(!$page->isErrorPage()): ?>
<?php if($page->description() != ''): ?>
<meta name="description" content="<?php echo html($page->description()) ?>" />
<?php else: ?>
<?php if($site->description() != ''): ?>
<meta name="description" content="<?php echo html($site->description()) ?>" />
<?php endif ?>
<?php endif ?>
<?php if($page->keywords() != ''): ?>
<meta name="keywords" content="<?php echo preg_replace('!\s+!', ' ', preg_replace('/\s*,+\s*/', ', ', $page->keywords())) ?>" />
<?php else: ?>
<?php if($site->keywords() != ''): ?>
<meta name="keywords" content="<?php echo preg_replace('!\s+!', ' ', preg_replace('/\s*,+\s*/', ', ', $site->keywords())) ?>" />
<?php endif ?>
<?php endif ?>
<link rel="canonical" href="<?php echo $page->url() ?>" />
<meta property="og:url" content="<?php echo $page->url() ?>">
<meta property="og:image" content="<?php if($page->hasImages()): ?><?php if($page->images()->first()->exif()->isColor()): ?><?php echo $page->images()->first()->url() ?><?php else: ?><?php echo url('assets/images/logo.png') ?><?php endif ?><?php else: ?><?php echo url('assets/images/logo.png') ?><?php endif ?>">
<?php if($page->description() != ''): ?>
<meta property="og:description" content="<?php echo html($page->description()) ?>" />
<?php else: ?>
<?php if($site->description() != ''): ?>
<meta property="og:description" content="<?php echo html($site->description()) ?>" />
<?php endif ?>
<?php endif ?>
<meta property="og:title" content="<?php echo $page->title()->html() ?> - <?php echo $site->title()->html() ?>">
<meta property="og:site_name" content="<?php echo $site->title()->html() ?>">
<meta property="og:see_also" content="<?php echo url('') ?>">
<meta itemprop="name" content="<?php echo $page->title()->html() ?> - <?php echo $site->title()->html() ?>">
<?php if($page->description() != ''): ?>
<meta itemprop="description" content="<?php echo html($page->description()) ?>" />
<?php else: ?>
<?php if($site->description() != ''): ?>
<meta itemprop="description" content="<?php echo html($site->description()) ?>" />
<?php endif ?>
<?php endif ?>
<meta itemprop="image" content="<?php if($page->hasImages()): ?><?php if($page->images()->first()->exif()->isColor()): ?><?php echo $page->images()->first()->url() ?><?php else: ?><?php echo url('assets/images/logo.png') ?><?php endif ?><?php else: ?><?php echo url('assets/images/logo.png') ?><?php endif ?>">
<meta name="twitter:card" content="summary">
<meta name="twitter:url" content="<?php echo $page->url() ?>">
<meta name="twitter:title" content="<?php echo $page->title()->html() ?> - <?php echo $site->title()->html() ?>">
<?php if($page->description() != ''): ?>
<meta name="twitter:description" content="<?php echo html($page->description()) ?>" />
<?php else: ?>
<?php if($site->description() != ''): ?>
<meta name="twitter:description" content="<?php echo html($site->description()) ?>" />
<?php endif ?>
<?php endif ?>
<meta name="twitter:image" content="<?php if($page->hasImages()): ?><?php if($page->images()->first()->exif()->isColor()): ?><?php echo $page->images()->first()->url() ?><?php else: ?><?php echo url('assets/images/logo.png') ?><?php endif ?><?php else: ?><?php echo url('assets/images/logo.png') ?><?php endif ?>">
<?php endif ?>
<!-- Bootstrap -->
<?php echo css('assets/css/bootstrap.min.css') ?>

<!-- Custom Styles -->
<?php echo css('assets/css/main.css') ?>

<?php echo css('http://fonts.googleapis.com/css?family=Lobster') ?>

<?php echo css('http://fonts.googleapis.com/css?family=Courgette') ?>

<?php echo css('http://fonts.googleapis.com/css?family=Coming+Soon') ?>

<?php echo css('assets/css/pace.css'); ?>

<style>
body {
background: url("<?php function rand_img($dir = 'assets/images/bg/img') { $imagesDir = $dir.'/'; $images = glob($imagesDir . '*.{png,PNG,jpg,JPG}', GLOB_BRACE); return $images[array_rand($images)]; } echo url(rand_img()) ?>") no-repeat center center fixed;
-webkit-background-size: cover;
-moz-background-size: cover;
-o-background-size: cover;
background-size: cover;
background-color: #CCCCCC;
}
<?php if(($page->isHomePage()) OR ($page->isErrorPage())): ?>
.p-title {
border-bottom:3px dashed #4183D7;
padding-bottom: 20px;
margin-bottom: 20px;
}
<?php endif ?>
</style>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<?php echo js('assets/js/jquery.min.js') ?>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<?php echo js('assets/js/bootstrap.min.js') ?>

<?php echo js('assets/js/site.js') ?>

<?php echo js('assets/js/pace.js'); ?>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<noscript>
<meta http-equiv="refresh" content="0;url=<?php echo url('assets/js/test.html') ?>">
</noscript>
</head>
<body>
<nav class="navbar navbar-default navbar-static-top NavFootOpacity">
<div class="container">
<div class="navbar-header">
<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<a id="ChangeColor" class="navbar-brand logo" href="<?php echo url('') ?>" title="Go to <?php echo $site->title()->html() ?> Home"><?php echo $site->title()->html() ?></a>
</div>
<?php snippet('Menu') ?>
</div>
</nav>