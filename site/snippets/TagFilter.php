<?php
function truncate($string, $length, $dots = "...") {
return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
}
?>
<?php $tag = urldecode(param('tag')); ?>
<?php $articles = $page->children()->visible()->filterBy('tags', $tag, ',')->sortBy('date', 'desc')->paginate(5); ?>
<?php echo '<h1>#'.$tag.'</h1>'; ?>
<?php if($articles == ''): ?>
<?php snippet('OnPageSearch') ?>
<p class="cntr">There are no pages/articles tagged with "<?php echo $tag ?>" in this page.</p>
<?php endif ?>
<?php if($articles != ''): ?>
<?php snippet('OnPageSearch') ?>
<p class="cntr">Pages/Articles tagged with "<?php echo $tag ?>" in this page.</p>
<?php foreach($articles as $article): ?>
<div class="panel panel-primary">
<div class="panel-heading">
<center>
<h2 class="TagPage"><a href="<?php echo $article->url() ?>"><?php echo truncate($article->title(), 30) ?></a></h2>
<?php if($article->date('F d, Y') != ''): ?><p class="PageInfo"><span><a href="<?php echo url('?q='.$article->date('o-m-d')); ?>"><?php echo $article->date('F jS, Y'); ?></a><?php if($article->author() != ''): ?> &dash; <a href="<?php echo url('?q='.$article->author()); ?>"><?php echo $article->author(); ?></a><?php endif ?></span></p>
<?php else: ?>
<?php if($article->author() != ''): ?><p class="PageInfo"><span><a href="<?php echo url('?q='.$article->author()); ?>"><?php echo $article->author(); ?></a></span></p><?php endif ?>
<?php endif ?>
</center>
</div>
<div class="panel-body">
<div class="row">
<div class="col-sm-6">
<?php if($image = $article->images()->sortBy('sort', 'asc')->first()): ?>
<a href="<?php echo $article->url() ?>">
<img src="<?php echo $image->url() ?>" alt="<?php echo $article->title()->html() ?>" >
</a>
<?php else: ?>
<?php if($site->simage() != 'true' && $site->simage() != 'True' && $site->simage() != 'TRUE' && $site->simage() != 'yes' && $site->simage() != 'Yes' && $site->simage() != 'YES'): ?>
<a href="<?php echo $article->url() ?>">
<img src="<?php echo url('assets/images/img-na.png') ?>" alt="Image Not Available" >
</a>
<?php else: ?>
<?php
$jsrc = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=".urlencode($article->title())."&userip=".$_SERVER['REMOTE_ADDR'];
$jset = json_decode(@file_get_contents($jsrc), true);
$rand = rand(0, 8);
?>
<?php if(!empty($jset["responseData"]["results"])): ?>
<a href="<?php echo $article->url() ?>">
<img src="<?php echo $jset["responseData"]["results"][$rand]["url"]; ?>" alt="<?php echo $jset["responseData"]["results"][$rand]["title"]; ?>" onError="this.onerror=null;this.src='<?php echo url('assets/images/img-na.png') ?>'; this.onerror=null;this.alt='Image Not Available';">
</a>
<?php else: ?>
<a href="<?php echo $article->url() ?>">
<img src="<?php echo url('assets/images/img-na.png') ?>" alt="Image Not Available" >
</a>
<?php endif ?>
<?php endif ?>
<?php endif ?>
</div>
<div class="col-sm-6">
<p class="TagPage"><?php if(truncate($article->text(), 200) != '') { echo truncate($article->text(), 200); } else { echo 'This page/article has no contents.'; } ?><a href="<?php echo $article->url() ?>" class="rm-btn btn-ol btn btn-primary btn-outline">read&nbsp;this&nbsp;→</a></p>
<?php if ($article->tags() != ''): ?>
<hr>
<?php foreach(str::split($article->tags()) as $tag): ?>
<a class="tags-btn btn btn-<?php if(urldecode(param('tag')) == $tag) { echo 'primary'; } else { echo 'default'; } ?>" href="<?php echo $article->parent()->url() . '/tag:' . urlencode($tag) ?>" role="button"><?php echo $tag; ?></a>
<?php endforeach ?>
<?php endif ?>
</div>
</div>
</div>
</div>
<?php endforeach ?>
<?php endif ?>
<?php if($articles->pagination()->hasPages()): ?>
<ul class="TagFilterPager pager">
<?php if($articles->pagination()->hasPrevPage()): ?>
<li class="previous" title="Previous">
<a href="<?php echo $articles->pagination()->prevPageURL() ?>"><span class="PECA glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>
</li>
<?php endif ?>
<?php if($articles->pagination()->hasNextPage()): ?>
<li class="next" title="Next">
<a href="<?php echo $articles->pagination()->nextPageURL() ?>"><span class="PECA glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
</li>
<?php endif ?>
</ul>
<?php endif ?>