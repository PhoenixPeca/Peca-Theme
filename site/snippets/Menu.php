<div id="navbar" class="navbar-collapse collapse">
<ul class="nav navbar-nav navbar-right">
<?php snippet('SearchBar') ?>
<?php foreach($pages->visible() as $p): ?>
<li<?php e($p->isOpen(), ' class="active"') ?>><a href="<?php echo $p->url() ?>"><?php echo $p->title()->html() ?><?php $LimitCount = 100; $LCSyntax = $LimitCount - 1; if($p->hasVisibleChildren()): if($p->children()->visible()->count() >= $LimitCount) { echo '<span title="Specifically, this page has '.$p->children()->visible()->count().' sub page'; if($p->children()->visible()->count() !== 1) { echo 's';} echo '" class="badge pull-right navbar-badge">'.$LCSyntax.'+</span>';} else { echo '<span title="This page has '.$p->children()->visible()->count().' sub page'; if($p->children()->visible()->count() !== 1) { echo 's';} echo '" class="badge navbar-badge">'.$p->children()->visible()->count().'</span>';} endif?></a></li>
<?php endforeach ?>
</ul>
</div>