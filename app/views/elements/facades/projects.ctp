<section class="project mini list">
<h1>Customers</h1>
<ul class="tab_hooks">
<?php foreach ($projects as $key=>$p): ?><li><a href="<?php echo $key ?>_prj"><?php echo Inflector::humanize($key) ?></a></li><?php endforeach ?>
</ul>
<?php foreach ($projects as $key=>$prjs): ?>
<section id="<?php echo $key ?>_prj" class="tab_page">
<h1><?php echo Inflector::humanize($key) ?> Projects</h1>
<?php if (!empty($prjs)): ?>
<?php foreach ($prjs as $project): ?>
<article>
<h1><?php echo $html->link(
	$project['Customer']['company_name'],
	array('controller'=>'customers','action'=>'view',$project['Customer']['id'])
) ?></h1>
<ul>
<?php foreach ($project['Service'] as $service): ?>
<li><?php echo $html->link($service['title'],array('controller'=>'services','action'=>'view',$service['id']),array('class'=>'modalAJAX')) ?></li>
<?php endforeach ?>
</ul>
</article>
<?php endforeach ?>
<?php else: ?>
<article><h1>No Projects</h1></article>
<?php endif ?>
</section>
<?php endforeach ?>
</section>
