<?php snippet('Header') ?>
<div class="mrgn">
<div class="jmbtrn-main container jumbotron BodyStyle">
<?php if(param('tag')): ?>
<?php if(get('q') == ''): ?>
<?php snippet('TagFilter') ?>
<?php else: ?>
<?php if(get('q') != ''): ?>
<?php snippet('Search') ?>
<?php endif ?>
<?php endif ?>
<?php else: ?>
<?php if(get('q') == ''): ?>
<?php snippet('SiteContent') ?>
<?php else: ?>
<?php snippet('Search') ?>
<?php endif ?>
<?php if(get('q') == ''): ?>
<?php snippet('ArticleNavigation') ?>
<?php snippet('Tags') ?>
<?php snippet('SubPages') ?>
<?php if($page->comments() == "true"): ?>
<?php snippet('Disqus', array('disqus_shortname' => $site->disqusSN())) ?>
<?php endif ?>
<?php endif ?>
<?php endif ?>
</div>
</div>
<?php snippet('Footer') ?>