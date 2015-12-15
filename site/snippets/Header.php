<?php function rand_img($dir = 'assets/images/bg/img') { $imagesDir = $dir.'/'; $images = glob($imagesDir . '*.{png,PNG,jpg,JPG}', GLOB_BRACE); return $images[array_rand($images)]; }  ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
<link rel="search" type="application/opensearchdescription+xml" title="<?php echo htmlspecialchars($site->title()->html(), ENT_QUOTES, 'UTF-8') ?>" href="<?php echo url('opensearch.php') ?>" />
<title><?php echo htmlspecialchars($page->title()->html(), ENT_QUOTES, 'UTF-8') ?> - <?php echo htmlspecialchars($site->title()->html(), ENT_QUOTES, 'UTF-8') ?></title>
<?php if(!$page->isErrorPage()): ?>
<?php if($page->description() != ''): ?>
<meta name="description" content="<?php echo htmlspecialchars(html($page->description()), ENT_QUOTES, 'UTF-8') ?>" />
<?php else: ?>
<?php if($site->description() != ''): ?>
<meta name="description" content="<?php echo htmlspecialchars(html($site->description()), ENT_QUOTES, 'UTF-8') ?>" />
<?php endif ?>
<?php endif ?>
<?php if($page->keywords() != ''): ?>
<meta name="keywords" content="<?php echo htmlspecialchars(preg_replace('!\s+!', ' ', preg_replace('/\s*,+\s*/', ', ', $page->keywords())), ENT_QUOTES, 'UTF-8') ?>" />
<?php else: ?>
<?php if($site->keywords() != ''): ?>
<meta name="keywords" content="<?php echo htmlspecialchars(preg_replace('!\s+!', ' ', preg_replace('/\s*,+\s*/', ', ', $site->keywords())), ENT_QUOTES, 'UTF-8') ?>" />
<?php endif ?>
<?php endif ?>
<link rel="canonical" href="<?php echo $page->url() ?>" />
<meta property="og:url" content="<?php echo $page->url() ?>">
<meta property="og:image" content="<?php if($page->hasImages()): ?><?php if($page->images()->first()->exif()->isColor()): ?><?php echo $page->images()->first()->url() ?><?php else: ?><?php echo url('assets/images/logo.png') ?><?php endif ?><?php else: ?><?php echo url('assets/images/logo.png') ?><?php endif ?>">
<?php if($page->description() != ''): ?>
<meta property="og:description" content="<?php echo htmlspecialchars(html($page->description()), ENT_QUOTES, 'UTF-8') ?>" />
<?php else: ?>
<?php if($site->description() != ''): ?>
<meta property="og:description" content="<?php echo htmlspecialchars(html($site->description()), ENT_QUOTES, 'UTF-8') ?>" />
<?php endif ?>
<?php endif ?>
<meta property="og:title" content="<?php echo htmlspecialchars($page->title()->html(), ENT_QUOTES, 'UTF-8') ?> - <?php echo htmlspecialchars($site->title()->html(), ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:site_name" content="<?php echo htmlspecialchars($site->title()->html(), ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:see_also" content="<?php echo url('') ?>">
<meta itemprop="name" content="<?php echo htmlspecialchars($page->title()->html(), ENT_QUOTES, 'UTF-8') ?> - <?php echo htmlspecialchars($site->title()->html(), ENT_QUOTES, 'UTF-8') ?>">
<?php if($page->description() != ''): ?>
<meta itemprop="description" content="<?php echo htmlspecialchars(html($page->description()), ENT_QUOTES, 'UTF-8') ?>" />
<?php else: ?>
<?php if($site->description() != ''): ?>
<meta itemprop="description" content="<?php echo htmlspecialchars(html($site->description()), ENT_QUOTES, 'UTF-8') ?>" />
<?php endif ?>
<?php endif ?>
<meta itemprop="image" content="<?php if($page->hasImages()): ?><?php if($page->images()->first()->exif()->isColor()): ?><?php echo $page->images()->first()->url() ?><?php else: ?><?php echo url('assets/images/logo.png') ?><?php endif ?><?php else: ?><?php echo url('assets/images/logo.png') ?><?php endif ?>">
<meta name="twitter:card" content="summary">
<meta name="twitter:url" content="<?php echo $page->url() ?>">
<meta name="twitter:title" content="<?php echo htmlspecialchars($page->title()->html(), ENT_QUOTES, 'UTF-8') ?> - <?php echo htmlspecialchars($site->title()->html(), ENT_QUOTES, 'UTF-8') ?>">
<?php if($page->description() != ''): ?>
<meta name="twitter:description" content="<?php echo htmlspecialchars(html($page->description()), ENT_QUOTES, 'UTF-8') ?>" />
<?php else: ?>
<?php if($site->description() != ''): ?>
<meta name="twitter:description" content="<?php echo htmlspecialchars(html($site->description()), ENT_QUOTES, 'UTF-8') ?>" />
<?php endif ?>
<?php endif ?>
<meta name="twitter:image" content="<?php if($page->hasImages()): ?><?php if($page->images()->first()->exif()->isColor()): ?><?php echo $page->images()->first()->url() ?><?php else: ?><?php echo url('assets/images/logo.png') ?><?php endif ?><?php else: ?><?php echo url('assets/images/logo.png') ?><?php endif ?>">
<?php endif ?>
<!-- Bootstrap -->
<?php echo css('assets/css/bootstrap.min.css') ?>

<!-- Custom Styles -->
<?php echo css('assets/css/main.css') ?>

<?php echo css('https://fonts.googleapis.com/css?family=Lobster') ?>

<?php echo css('https://fonts.googleapis.com/css?family=Courgette') ?>

<?php echo css('https://fonts.googleapis.com/css?family=Coming+Soon') ?>

<?php echo css('assets/css/pace.css'); ?>

<style>
body {
<?php if(@rand_img()): ?>
background: url("<?php echo url(rand_img()) ?>") no-repeat center center fixed;
-webkit-background-size: cover;
-moz-background-size: cover;
-o-background-size: cover;
background-size: cover;
<?php endif ?>
background-color: #CCCCCC;
transition: background-color 5s;
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
<a id="ChangeColor" class="navbar-brand logo" href="<?php echo url('') ?>" title="Go to <?php echo htmlspecialchars($site->title()->html(), ENT_QUOTES, 'UTF-8') ?> Home"><?php echo htmlspecialchars($site->title()->html(), ENT_QUOTES, 'UTF-8') ?></a>
</div>
<?php snippet('Menu') ?>
</div>
</nav>