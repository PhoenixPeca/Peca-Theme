<?php if($page->tags() != ''): ?>
<div class="panel panel-primary">
<div class="panel-heading">
Tags
</div>
<div class="panel-body">
<?php foreach(str::split($page->tags()) as $tag): ?>
<a class="tags-btn btn btn-default" href="<?php echo $page->parent()->url() . '/tag:' . urlencode($tag) ?>" role="button"><?php echo $tag; ?></a>
<?php endforeach ?>
</div>
</div>
<?php endif ?>