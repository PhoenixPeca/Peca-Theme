<?php
function truncate($string, $length, $dots = "...") {
return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
}
?>
<?php $pcl = $page->children()->visible()->flip()->paginate(9)?>
<?php if($page->hasVisibleChildren()): ?>
<div class="row">
<?php foreach($pcl as $pc): ?>
<div class="item-fancy col-sm-6 col-md-4">
<div onclick="location.href='<?php echo $pc->url() ?>';" style="cursor:pointer;" title="<?php echo $pc->title() ?>" class="opaction thumbnail">
<?php if($image = $pc->images()->sortBy('sort', 'asc')->first()): ?>
<a href="<?php echo $pc->url() ?>">
<img src="<?php echo thumb($image, array('width' => 331, 'height' => 187, 'crop' => true, 'quality' => 50))->url() ?>" alt="<?php echo $pc->title()->html() ?>" >
</a>
<?php else: ?>
<?php if($site->simage() != 'true' && $site->simage() != 'True' && $site->simage() != 'TRUE' && $site->simage() != 'yes' && $site->simage() != 'Yes' && $site->simage() != 'YES'): ?>
<a href="<?php echo $pc->url() ?>">
<img src="<?php echo url('assets/images/img-na.png') ?>" alt="Image Not Available" >
</a>
<?php else: ?>
<?php
$jsrc = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=".urlencode($pc->title())."&userip=".$_SERVER['REMOTE_ADDR'];
$jset = json_decode(@file_get_contents($jsrc), true);
$rand = rand(0, 4);
?>
<?php if(!empty($jset["responseData"]["results"])): ?>
<a href="<?php echo $pc->url() ?>">
<img src="<?php echo $jset["responseData"]["results"][$rand]["url"]; ?>" alt="<?php echo $jset["responseData"]["results"][$rand]["title"]; ?>" onError="this.onerror=null;this.src='<?php echo url('assets/images/img-na.png') ?>'; this.onerror=null;this.alt='Image Not Available';">
</a>
<?php else: ?>
<a href="<?php echo $pc->url() ?>">
<img src="<?php echo url('assets/images/img-na.png') ?>" alt="Image Not Available" >
</a>
<?php endif ?>
<?php endif ?>
<?php endif ?>
<div class="caption ofh">
<a href="<?php echo $pc->url() ?>"><h3><?php echo truncate($pc->title(), 30) ?></h3></a>
<?php if($pc->date('F d, Y') != ''): ?><p class="PageInfo"><span><a href="<?php echo url('?q='.$pc->date('o-m-d').'&type=date'); ?>"><?php echo $pc->date('F jS, Y'); ?></a><?php if($pc->author() != ''): ?> &dash; <a href="<?php echo url('?q='.$pc->author().'&type=author'); ?>"><?php echo $pc->author(); ?></a><?php endif ?></span></p>
<?php else: ?>
<?php if($pc->author() != ''): ?><p class="PageInfo"><span><a href="<?php echo url('?q='.$pc->author().'&type=author'); ?>"><?php echo $pc->author(); ?></a></span></p><?php endif ?>
<?php endif ?>
<?php if($pc->description() != ''): ?>
<a href="<?php echo $pc->url() ?>">
<p><?php echo truncate($pc->description(), 100) ?></p>
</a>
<?php else: ?>
<a href="<?php echo $pc->url() ?>">
<p><?php echo truncate($pc->text(), 100) ?></p>
</a>
<?php endif ?>
</div>
</div>
</div>
<?php endforeach ?>
</div>
<?php endif ?>
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