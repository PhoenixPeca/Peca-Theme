<?php if($page->parents()->count() !== 0): ?>
<ul class="pager">
<?php if($next = $page->nextVisible()): ?>
<li class="previous" title="<?php echo $next->title() ?>">
<a href="<?php echo $next->url() ?>"><span class="PECA glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>
</li>
<?php endif ?>
<?php if($page->parent() != $page->isHomePage()): ?>
<li title="Go back to <?php echo $page->parent()->title() ?>">
<a href="<?php echo $page->parent()->url() ?>"><span class="PECA glyphicon glyphicon-chevron-up" aria-hidden="true"></span></a>
</li>
<?php endif ?>
<?php if($prev = $page->prevVisible()): ?>
<li class="next" title="<?php echo $prev->title() ?>">
<a href="<?php echo $prev->url() ?>"><span class="PECA glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
</li>
<?php endif ?>
</ul>
<?php endif ?>