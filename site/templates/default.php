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
<?php if($page->title() == 'Contact'): ?>
<?php snippet('ContactForm') ?>
<?php endif ?>
<?php else: ?>
<?php snippet('Search') ?>
<?php endif ?>
<?php if(get('q') == ''): ?>
<?php snippet('SubPages') ?>
<?php if($page->isHomePage()): ?>
<?php snippet('LatestProjects') ?>
<?php endif ?>
<?php endif ?>
<?php endif ?>
</div>
</div>
<?php snippet('Footer') ?>