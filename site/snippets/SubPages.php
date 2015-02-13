<?php if($page->hasVisibleChildren()): ?>
<div class="panel panel-primary">
<div class="panel-heading">
Sub Page<?php if($page->children()->visible()->count() !== 1) { echo 's';} ?>
</div>
<div class="panel-body">
<?php $pcl = $page->children()->visible()->flip()->paginate(10)?>
<?php foreach($pcl as $pc): ?>
<a href="<?php echo $pc->url() ?>"<?php if($pc->text() != '') { echo 'title="'.$pc->text()->excerpt(100).'" '; } ?> class="sp-btn btn-ol btn btn-primary btn-outline"><?php echo $pc->title()->html() ?><?php $LimitCount = 100; $LCSyntax = $LimitCount - 1; if($pc->hasVisibleChildren()): if($pc->children()->visible()->count() >= $LimitCount) { echo '<span title="Specifically, this page has '.$pc->children()->visible()->count().' sub page'; if($pc->children()->visible()->count() !== 1) { echo 's';} echo '" class="badge pull-right sp-badge">'.$LCSyntax.'+</span>';} else { echo '<span title="This page has '.$pc->children()->visible()->count().' sub page'; if($pc->children()->visible()->count() !== 1) { echo 's';} echo '" class="badge pull-right sp-badge">'.$pc->children()->visible()->count().'</span>';} endif?></a>
<?php endforeach ?>
<?php if($pcl->pagination()->hasPages()): ?>
<ul class="pager-panel pager">
<?php if($pcl->pagination()->hasPrevPage()): ?>
<li class="previous" title="Previous">
<a href="<?php echo $pcl->pagination()->prevPageURL() ?>"><span class="PECA glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>
</li>
<?php endif ?>
<?php if($pcl->pagination()->hasNextPage()): ?>
<li class="next" title="Next">
<a href="<?php echo $pcl->pagination()->nextPageURL() ?>"><span class="PECA glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
</li>
<?php endif ?>
</ul>
<?php endif ?>
</div>
</div>
<?php endif ?>