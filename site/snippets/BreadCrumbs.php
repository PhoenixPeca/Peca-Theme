<?php $peca = $site; ?>
<?php if($page->isHomePage() == false && $page->isErrorPage() == false): ?>
<?php if($peca->breadcrumb()->count() > 0): ?>
<ol class="breadcrumb">
<?php foreach($peca->breadcrumb() as $crumb): ?>
<li<?php if($crumb->isActive() == true): ?> class="active"<?php endif ?>><?php if($crumb->isActive() == false): ?><a href="<?php echo $crumb->url() ?>"><?php endif ?><?php echo html($crumb->title()) ?><?php if($crumb->isActive() == false): ?></a><?php endif ?></li>
<?php endforeach ?>
</ol>
<?php endif ?>
<?php endif ?>