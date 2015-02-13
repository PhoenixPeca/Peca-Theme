<?php $tag = urldecode(param('tag')); ?>
<?php $articles = $page->children()->visible()->filterBy('tags', $tag, ',')->sortBy('date', 'desc')->paginate(5); ?>
<?php echo '<h1>#'.$tag.'</h1>'; ?>
<?php if($articles == ''): ?>
<p class="cntr">There are no pages/articles tagged with "<?php echo $tag ?>" in this page.</p>
<?php endif ?>
<?php if($articles != ''): ?>
<p class="cntr">Pages/Articles tagged with "<?php echo $tag ?>" in this page.</p>
<?php foreach($articles as $article): ?>
<div class="panel panel-primary">
<div class="panel-heading">
<center>
<h2 class="TagPage"><a href="<?php echo $article->url() ?>"><?php echo html($article->title()) ?></a></h2>
<?php if($article->date('F d, Y') != ''): ?><p class="PageInfo"><span><a href="<?php echo url('?q='.$article->date('o-m-d')); ?>"><?php echo $article->date('F jS, Y'); ?></a><?php if($article->author() != ''): ?> &dash; <a href="<?php echo url('?q='.$article->author()); ?>"><?php echo $article->author(); ?></a><?php endif ?></span></p>
<?php else: ?>
<?php if($article->author() != ''): ?><p class="PageInfo"><span><a href="<?php echo url('?q='.$article->author()); ?>"><?php echo $article->author(); ?></a></span></p><?php endif ?>
<?php endif ?>
</center>
</div>
<div class="panel-body">
<?php if($image = $article->images()->sortBy('sort', 'asc')->first()): ?>
<a href="<?php echo $article->url() ?>">
<img src="<?php echo $image->url() ?>" alt="<?php echo $article->title()->html() ?>" >
</a>
<?php endif ?>
<p class="TagPage"><?php if($article->text()->excerpt(200) != '') { echo $article->text()->excerpt(200); } else { echo 'This page/article has no contents.'; } ?><a href="<?php echo $article->url() ?>" class="rm-btn btn-ol btn btn-primary btn-outline">read&nbsp;this&nbsp;→</a></p>
<hr>
<?php if ($article->tags() != ''): ?>
<?php foreach(str::split($article->tags()) as $tag): ?>
<a class="tags-btn btn btn-<?php if(urldecode(param('tag')) == $tag) { echo 'primary'; } else { echo 'default'; } ?>" href="<?php echo $article->parent()->url() . '/tag:' . urlencode($tag) ?>" role="button"><?php echo $tag; ?></a>
<?php endforeach ?>
<?php endif ?>
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