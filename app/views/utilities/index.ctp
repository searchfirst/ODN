<h1>Utilities</h1>
<div class="utilities list">
<ul>
<?php foreach ($utilities as $utility): ?>
<li><?php echo $this->Html->link($utility['title'], $utility['href']) ?></li>
<?php endforeach ?>
</ul>
</div>
