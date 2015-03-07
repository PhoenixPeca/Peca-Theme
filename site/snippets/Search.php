<?php
function truncate($string, $length, $dots = "...") {
return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
}
?>
<?php
$query = get('q');
$tag = urldecode(param('tag'));
?>
<?php if($tag != ''): ?>
<?php $results = $page->search($query)->visible()->filterBy('tags', $tag, ',')->sortBy('date', 'desc'); ?>
<?php else: ?>
<?php if($page->isHomePage()): ?>
<?php $results = $site->search($query)->visible()->sortBy('date', 'desc'); ?>
<?php else: ?>
<?php if($page->isErrorPage()): ?>
<?php $results = $site->search($query)->visible()->sortBy('date', 'desc'); ?>
<?php else: ?>
<?php if($tag == ''): ?>
<?php if($page->hasVisibleChildren()): ?>
<?php $results = $page->search($query)->visible()->sortBy('date', 'desc'); ?>
<?php endif ?>
<?php if($page->isHomePage() == false && $page->isErrorPage() == false): ?>
<?php if($page->hasVisibleChildren() == false): ?>
<?php $results = $site->search($query)->visible()->sortBy('date', 'desc'); ?>
<?php endif ?>
<?php endif ?>
<?php endif ?>
<?php endif ?>
<?php endif ?>
<?php endif ?>
<?php if($tag != ''): ?>
<h1><?php echo '#'.$tag.' &raquo; '.urldecode($query) ?></h1>
<?php endif ?>
<?php if($tag == ''): ?>
<h1><?php echo urldecode($query) ?></h1>
<?php endif ?>
<?php if($results == ''): ?>
<?php if($tag != ''): ?>
<?php snippet('OnPageSearch') ?>
<p class="cntr">There are no pages/articles tagged with "<?php echo $tag ?>" that matches with "<?php echo $query ?>" <?php if($tag != ''): ?>in this page<?php else: ?><?php if($page->isHomePage()): ?>in this site<?php else: ?><?php if($page->isErrorPage()): ?>in this site<?php else: ?><?php if($tag == ''): ?><?php if($page->hasVisibleChildren()): ?>in this page<?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php if($tag == ''): ?><?php if($page->isHomePage() == false && $page->isErrorPage() == false): ?><?php if($page->hasVisibleChildren() == false): ?>in this site<?php endif ?><?php endif ?><?php endif ?>.</p>
<?php else: ?>
<?php snippet('OnPageSearch') ?>
<p class="cntr">There are no pages/articles that match with "<?php echo $query ?>" <?php if($tag != ''): ?>in this page<?php else: ?><?php if($page->isHomePage()): ?>in this site<?php else: ?><?php if($page->isErrorPage()): ?>in this site<?php else: ?><?php if($tag == ''): ?><?php if($page->hasVisibleChildren()): ?>in this page<?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php if($tag == ''): ?><?php if($page->isHomePage() == false && $page->isErrorPage() == false): ?><?php if($page->hasVisibleChildren() == false): ?>in this site<?php endif ?><?php endif ?><?php endif ?>.</p>
<?php endif ?>
<?php endif ?>
<?php if($results != ''): ?>
<?php if($tag != ''): ?>
<?php snippet('OnPageSearch') ?>
<p class="cntr">Pages/Articles tagged with "<?php echo $tag ?>" that matches with "<?php echo $query ?>" <?php if($tag != ''): ?>in this page<?php else: ?><?php if($page->isHomePage()): ?>in this site<?php else: ?><?php if($page->isErrorPage()): ?>in this site<?php else: ?><?php if($tag == ''): ?><?php if($page->hasVisibleChildren()): ?>in this page<?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php if($tag == ''): ?><?php if($page->isHomePage() == false && $page->isErrorPage() == false): ?><?php if($page->hasVisibleChildren() == false): ?>in this site<?php endif ?><?php endif ?><?php endif ?>.</p>
<?php else: ?>
<?php snippet('OnPageSearch') ?>
<p class="cntr">Pages/Articles that match with "<?php echo $query ?>" <?php if($tag != ''): ?>in this page<?php else: ?><?php if($page->isHomePage()): ?>in this site<?php else: ?><?php if($page->isErrorPage()): ?>in this site<?php else: ?><?php if($tag == ''): ?><?php if($page->hasVisibleChildren()): ?>in this page<?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php if($tag == ''): ?><?php if($page->isHomePage() == false && $page->isErrorPage() == false): ?><?php if($page->hasVisibleChildren() == false): ?>in this site<?php endif ?><?php endif ?><?php endif ?>.</p>
<?php endif ?>
<?php foreach($results as $result): ?>
<div class="panel panel-primary">
<div class="panel-heading">
<center>
<h2 class="TagPage"><a href="<?php echo $result->url() ?>"><?php echo truncate($result->title(), 30) ?></a></h2>
<?php if($result->date('F d, Y') != ''): ?><p class="PageInfo"><span><a href="<?php echo url('?q='.$result->date('o-m-d')); ?>"><?php echo $result->date('F jS, Y'); ?></a><?php if($result->author() != ''): ?> &dash; <a href="<?php echo url('?q='.$result->author()); ?>"><?php echo $result->author(); ?></a><?php endif ?></span></p>
<?php else: ?>
<?php if($result->author() != ''): ?><p class="PageInfo"><span><a href="<?php echo url('?q='.$result->author()); ?>"><?php echo $result->author(); ?></a></span></p><?php endif ?>
<?php endif ?>
</center>
</div>
<div class="panel-body">
<div class="row">
<div class="col-sm-6">
<?php if($image = $result->images()->sortBy('sort', 'asc')->first()): ?>
<a href="<?php echo $result->url() ?>">
<img src="<?php echo $image->url() ?>" alt="<?php echo $result->title()->html() ?>" >
</a>
<?php else: ?>
<?php if($site->simage() != 'true' && $site->simage() != 'True' && $site->simage() != 'TRUE' && $site->simage() != 'yes' && $site->simage() != 'Yes' && $site->simage() != 'YES'): ?>
<a href="<?php echo $result->url() ?>">
<img src="<?php echo url('assets/images/img-na.png') ?>" alt="Image Not Available" >
</a>
<?php else: ?>
<?php
$jsrc = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=".urlencode($result->title())."&userip=".$_SERVER['REMOTE_ADDR'];
$jset = json_decode(@file_get_contents($jsrc), true);
$rand = rand(0, 4);
?>
<?php if(!empty($jset["responseData"]["results"])): ?>
<a href="<?php echo $result->url() ?>">
<img src="<?php echo $jset["responseData"]["results"][$rand]["url"]; ?>" alt="<?php echo $jset["responseData"]["results"][$rand]["title"]; ?>" onError="this.onerror=null;this.src='<?php echo url('assets/images/img-na.png') ?>'; this.onerror=null;this.alt='Image Not Available';">
</a>
<?php else: ?>
<a href="<?php echo $result->url() ?>">
<img src="<?php echo url('assets/images/img-na.png') ?>" alt="Image Not Available" >
</a>
<?php endif ?>
<?php endif ?>
<?php endif ?>
</div>
<div class="col-sm-6">
<p class="TagPage"><?php if(truncate($result->text(), 200) != '') { echo truncate($result->text(), 200); } else { echo 'This page/article has no contents.'; } ?><a href="<?php echo $result->url() ?>" class="rm-btn btn-ol btn btn-primary btn-outline">read&nbsp;this&nbsp;→</a></p>
<?php if ($result->tags() != ''): ?>
<hr>
<?php foreach(str::split($result->tags()) as $tag): ?>
<a class="tags-btn btn btn-<?php if(urldecode(param('tag')) == $tag) { echo 'primary'; } else { echo 'default'; } ?>" href="<?php echo $result->parent()->url() . '/tag:' . urlencode($tag) ?>" role="button"><?php echo $tag; ?></a>
<?php endforeach ?>
<?php endif ?>
</div>
</div>
</div>
</div>
<?php endforeach ?>
<?php endif ?>