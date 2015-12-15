<?php
function truncate($string, $length, $dots = "...") {
return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
}
?>
<?php
$query = get('q');
$type = get('type');
$tag = urldecode(param('tag'));
?>
<?php if($tag != ''): ?>
<?php if($type == 'author'): ?>
<?php $results = $page->search($query, 'author', array('words' => true))->visible()->filterBy('tags', $tag, ',')->sortBy('date', 'desc')->paginate(5, array('method' => 'query')); ?>
<?php $itmCount = $page->search($query, 'author', array('words' => true))->visible()->filterBy('tags', $tag, ',')->sortBy('date', 'desc')->count(); ?>
<?php else: ?>
<?php if($type == 'date'): ?>
<?php $results = $page->search($query, 'date', array('words' => true))->visible()->filterBy('tags', $tag, ',')->sortBy('date', 'desc')->paginate(5, array('method' => 'query')); ?>
<?php $itmCount = $page->search($query, 'date', array('words' => true))->visible()->filterBy('tags', $tag, ',')->sortBy('date', 'desc')->count(); ?>
<?php else: ?>
<?php $results = $page->search($query)->visible()->filterBy('tags', $tag, ',')->sortBy('date', 'desc')->paginate(5, array('method' => 'query')); ?>
<?php $itmCount = $page->search($query)->visible()->filterBy('tags', $tag, ',')->sortBy('date', 'desc')->count(); ?>
<?php endif ?>
<?php endif ?>
<?php else: ?>
<?php if($page->isHomePage()): ?>
<?php if($type == 'author'): ?>
<?php $results = $site->search($query, 'author', array('words' => true))->visible()->sortBy('date', 'desc')->paginate(5, array('method' => 'query')); ?>
<?php $itmCount = $site->search($query, 'author', array('words' => true))->visible()->sortBy('date', 'desc')->count(); ?>
<?php else: ?>
<?php if($type == 'date'): ?>
<?php $results = $site->search($query, 'date', array('words' => true))->visible()->sortBy('date', 'desc')->paginate(5, array('method' => 'query')); ?>
<?php $itmCount = $site->search($query, 'date', array('words' => true))->visible()->sortBy('date', 'desc')->count(); ?>
<?php else: ?>
<?php $results = $site->search($query)->visible()->sortBy('date', 'desc')->paginate(5, array('method' => 'query')); ?>
<?php $itmCount = $site->search($query)->visible()->sortBy('date', 'desc')->count(); ?>
<?php endif ?>
<?php endif ?>
<?php else: ?>
<?php if($page->isErrorPage()): ?>
<?php if($type == 'author'): ?>
<?php $results = $site->search($query, 'author', array('words' => true))->visible()->sortBy('date', 'desc')->paginate(5, array('method' => 'query')); ?>
<?php $itmCount = $site->search($query, 'author', array('words' => true))->visible()->sortBy('date', 'desc')->count(); ?>
<?php else: ?>
<?php if($type == 'date'): ?>
<?php $results = $site->search($query, 'date', array('words' => true))->visible()->sortBy('date', 'desc')->paginate(5, array('method' => 'query')); ?>
<?php $itmCount = $site->search($query, 'date', array('words' => true))->visible()->sortBy('date', 'desc')->count(); ?>
<?php else: ?>
<?php $results = $site->search($query)->visible()->sortBy('date', 'desc')->paginate(5, array('method' => 'query')); ?>
<?php $itmCount = $site->search($query)->visible()->sortBy('date', 'desc')->count(); ?>
<?php endif ?>
<?php endif ?>
<?php else: ?>
<?php if($tag == ''): ?>
<?php if($page->hasVisibleChildren()): ?>
<?php if($type == 'author'): ?>
<?php $results = $page->search($query, 'author', array('words' => true))->visible()->sortBy('date', 'desc')->paginate(5, array('method' => 'query')); ?>
<?php $itmCount = $page->search($query, 'author', array('words' => true))->visible()->sortBy('date', 'desc')->count(); ?>
<?php else: ?>
<?php if($type == 'date'): ?>
<?php $results = $page->search($query, 'date', array('words' => true))->visible()->sortBy('date', 'desc')->paginate(5, array('method' => 'query')); ?>
<?php $itmCount = $page->search($query, 'date', array('words' => true))->visible()->sortBy('date', 'desc')->count(); ?>
<?php else: ?>
<?php $results = $page->search($query)->visible()->sortBy('date', 'desc')->paginate(5, array('method' => 'query')); ?>
<?php $itmCount = $page->search($query)->visible()->sortBy('date', 'desc')->count(); ?>
<?php endif ?>
<?php endif ?>
<?php endif ?>
<?php if($page->isHomePage() == false && $page->isErrorPage() == false): ?>
<?php if($page->hasVisibleChildren() == false): ?>
<?php if($type == 'author'): ?>
<?php $results = $site->search($query, 'author', array('words' => true))->visible()->sortBy('date', 'desc')->paginate(5, array('method' => 'query')); ?>
<?php $itmCount = $site->search($query, 'author', array('words' => true))->visible()->sortBy('date', 'desc')->count; ?>
<?php else: ?>
<?php if($type == 'date'): ?>
<?php $results = $site->search($query, 'date', array('words' => true))->visible()->sortBy('date', 'desc')->paginate(5, array('method' => 'query')); ?>
<?php $itmCount = $site->search($query, 'date', array('words' => true))->visible()->sortBy('date', 'desc')->count; ?>
<?php else: ?>
<?php $results = $site->search($query)->visible()->sortBy('date', 'desc')->paginate(5, array('method' => 'query')); ?>
<?php $itmCount = $site->search($query)->visible()->sortBy('date', 'desc')->count; ?>
<?php endif ?>
<?php endif ?>
<?php endif ?>
<?php endif ?>
<?php endif ?>
<?php endif ?>
<?php endif ?>
<?php endif ?>
<?php if($tag != ''): ?>
<h1><?php echo '#'.htmlspecialchars($tag, ENT_QUOTES, 'UTF-8').' &raquo; '.htmlspecialchars(urldecode($query), ENT_QUOTES, 'UTF-8') ?></h1>
<?php endif ?>
<?php if($tag == ''): ?>
<h1><?php echo htmlspecialchars(urldecode($query), ENT_QUOTES, 'UTF-8') ?></h1>
<?php endif ?>
<?php if($results == ''): ?>
<?php if($tag != ''): ?>
<?php snippet('OnPageSearch') ?>
<p class="cntr">There are no pages/articles tagged with "<?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?>" that matches with "<?php echo htmlspecialchars($query, ENT_QUOTES, 'UTF-8') ?>" <?php if($tag != ''): ?>in this page<?php else: ?><?php if($page->isHomePage()): ?>in this site<?php else: ?><?php if($page->isErrorPage()): ?>in this site<?php else: ?><?php if($tag == ''): ?><?php if($page->hasVisibleChildren()): ?>in this page<?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php if($tag == ''): ?><?php if($page->isHomePage() == false && $page->isErrorPage() == false): ?><?php if($page->hasVisibleChildren() == false): ?>in this site<?php endif ?><?php endif ?><?php endif ?>.</p>
<?php else: ?>
<?php snippet('OnPageSearch') ?>
<p class="cntr">There are no pages/articles that matches with "<?php echo htmlspecialchars($query, ENT_QUOTES, 'UTF-8') ?>" <?php if($tag != ''): ?>in this page<?php else: ?><?php if($page->isHomePage()): ?>in this site<?php else: ?><?php if($page->isErrorPage()): ?>in this site<?php else: ?><?php if($tag == ''): ?><?php if($page->hasVisibleChildren()): ?>in this page<?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php if($tag == ''): ?><?php if($page->isHomePage() == false && $page->isErrorPage() == false): ?><?php if($page->hasVisibleChildren() == false): ?>in this site<?php endif ?><?php endif ?><?php endif ?>.</p>
<?php endif ?>
<?php endif ?>
<?php if($results != ''): ?>
<?php if($tag != ''): ?>
<?php snippet('OnPageSearch') ?>
<p class="cntr">There <?php if($itmCount == 1): ?>is<?php else: ?>are<?php endif ?> <?php echo htmlspecialchars($itmCount, ENT_QUOTES, 'UTF-8'); ?> page<?php if($itmCount != 1): ?>s<?php endif ?>/article<?php if($itmCount != 1): ?>s<?php endif ?> tagged with "<?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?>" that matches with "<?php echo htmlspecialchars($query, ENT_QUOTES, 'UTF-8') ?>" <?php if($tag != ''): ?>in this page<?php else: ?><?php if($page->isHomePage()): ?>in this site<?php else: ?><?php if($page->isErrorPage()): ?>in this site<?php else: ?><?php if($tag == ''): ?><?php if($page->hasVisibleChildren()): ?>in this page<?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php if($tag == ''): ?><?php if($page->isHomePage() == false && $page->isErrorPage() == false): ?><?php if($page->hasVisibleChildren() == false): ?>in this site<?php endif ?><?php endif ?><?php endif ?>.</p>
<?php else: ?>
<?php snippet('OnPageSearch') ?>
<p class="cntr">There <?php if($itmCount == 1): ?>is<?php else: ?>are<?php endif ?> <?php echo htmlspecialchars($itmCount, ENT_QUOTES, 'UTF-8'); ?> page<?php if($itmCount != 1): ?>s<?php endif ?>/article<?php if($itmCount != 1): ?>s<?php endif ?> that matches with "<?php echo htmlspecialchars($query, ENT_QUOTES, 'UTF-8') ?>" <?php if($tag != ''): ?>in this page<?php else: ?><?php if($page->isHomePage()): ?>in this site<?php else: ?><?php if($page->isErrorPage()): ?>in this site<?php else: ?><?php if($tag == ''): ?><?php if($page->hasVisibleChildren()): ?>in this page<?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php endif ?><?php if($tag == ''): ?><?php if($page->isHomePage() == false && $page->isErrorPage() == false): ?><?php if($page->hasVisibleChildren() == false): ?>in this site<?php endif ?><?php endif ?><?php endif ?>.</p>
<?php endif ?>
<?php foreach($results as $result): ?>
<div class="panel panel-primary">
<div class="panel-heading">
<center>
<h2 class="TagPage"><a href="<?php echo $result->url() ?>"><?php echo htmlspecialchars(truncate($result->title(), 30), ENT_QUOTES, 'UTF-8') ?></a></h2>
<?php if($result->date('F d, Y') != ''): ?><p class="PageInfo"><span><a href="<?php echo url('?q='.$result->date('o-m-d').'&type=date'); ?>"><?php echo htmlspecialchars($result->date('F jS, Y'), ENT_QUOTES, 'UTF-8') ?></a><?php if($result->author() != ''): ?> &dash; <a href="<?php echo url('?q='.$result->author().'&type=author'); ?>"><?php echo htmlspecialchars($result->author(), ENT_QUOTES, 'UTF-8') ?></a><?php endif ?></span></p>
<?php else: ?>
<?php if($result->author() != ''): ?><p class="PageInfo"><span><a href="<?php echo url('?q='.$result->author().'&type=author'); ?>"><?php echo htmlspecialchars($result->author(), ENT_QUOTES, 'UTF-8') ?></a></span></p><?php endif ?>
<?php endif ?>
</center>
</div>
<div class="panel-body">
<div class="row">
<div class="col-sm-6">
<?php if($image = $result->images()->sortBy('sort', 'asc')->first()): ?>
<a href="<?php echo $result->url() ?>">
<img src="<?php echo thumb($image, array('width' => 514, 'height' => 250, 'crop' => true, 'quality' => 50))->url() ?>" alt="<?php echo htmlspecialchars($result->title()->html(), ENT_QUOTES, 'UTF-8') ?>" >
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
<p class="TagPage">
<?php if(truncate($result->description(), 200) != ''): ?>
<?php echo htmlspecialchars(truncate($result->description(), 200), ENT_QUOTES, 'UTF-8') ?>
<?php else: ?>
<?php if(truncate($result->text(), 200) != ''): ?>
<?php echo htmlspecialchars(truncate($result->text(), 200), ENT_QUOTES, 'UTF-8') ?>
<?php else: ?>
This page/article has no contents.
<?php endif ?>
<?php endif ?>
<a href="<?php echo $result->url() ?>" class="rm-btn btn-ol btn btn-primary btn-outline">read&nbsp;this&nbsp;&#8594;</a></p>
<?php if ($result->tags() != ''): ?>
<hr>
<?php foreach(str::split($result->tags()) as $tag): ?>
<a class="tags-btn btn btn-<?php if(urldecode(param('tag')) == $tag) { echo 'primary'; } else { echo 'default'; } ?>" href="<?php echo $result->parent()->url() . '/tag:' . urlencode($tag) ?>" role="button"><?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?></a>
<?php endforeach ?>
<?php endif ?>
</div>
</div>
</div>
</div>
<?php endforeach ?>
<?php endif ?>
<?php if($results->pagination()->hasPages()): ?>
<ul class="TagFilterPager pager">
<?php if($results->pagination()->hasPrevPage()): ?>
<li class="previous" title="Previous">
<a href="<?php echo $results->pagination()->prevPageURL() ?>"><span class="PECA glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>
</li>
<?php endif ?>
<?php foreach($results->pagination()->range(5) as $paging): ?>
<li class="desktop-only">
<a href="<?php echo $results->pagination()->pageURL($paging); ?>"><?php echo htmlspecialchars($paging, ENT_QUOTES, 'UTF-8') ?></a>
</li>
<?php endforeach ?>
<?php if($results->pagination()->hasNextPage()): ?>
<li class="next" title="Next">
<a href="<?php echo $results->pagination()->nextPageURL() ?>"><span class="PECA glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
</li>
<?php endif ?>
</ul>
<?php endif ?>