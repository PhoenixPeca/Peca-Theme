<div class="MainSiteSoul">
<h1 class="p-title"><?php echo htmlspecialchars($page->title()->html(), ENT_QUOTES, 'UTF-8') ?></h1>
<?php snippet('BreadCrumbs') ?>
<?php if($page->date('F d, Y') != ''): ?><p class="PageInfo"><span><a href="<?php echo url('?q='.$page->date('o-m-d').'&type=date'); ?>"><?php echo htmlspecialchars($page->date('F jS, Y'), ENT_QUOTES, 'UTF-8') ?></a><?php if($page->author() != ''): ?> &dash; <a href="<?php echo url('?q='.$page->author().'&type=author'); ?>"><?php echo htmlspecialchars($page->author(), ENT_QUOTES, 'UTF-8') ?></a><?php endif ?></span></p>
<?php else: ?>
<?php if($page->author() != ''): ?><p class="PageInfo"><span><a href="<?php echo url('?q='.$page->author().'&type=author'); ?>"><?php echo htmlspecialchars($page->author(), ENT_QUOTES, 'UTF-8') ?></a></span></p><?php endif ?>
<?php endif ?>
<!--[if lte IE 9]>
<div class="alert alert-warning alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<strong>Warning!</strong> You are using an outdated browser that doesn't seem to fully support our site and many others. In this case, you can upgrade/update your <a href="http://browsehappy.com/" target="_blank">browser</a>.
</div>
<![endif]-->
<?php if($page->isErrorPage()): ?>
<?php snippet('OnPageSearch') ?>
<?php endif ?>
<?php echo $page->text()->kirbytext() ?>
</div>