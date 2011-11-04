<h2>Websites</h2>
<ul class="item_list">
<?php foreach ($websites as $i=>$website): ?>
<li><?php echo $this->Html->link($website['Website']['uri'],"/websites/view/{$website['Website']['id']}") ?></li>
<?php endforeach ?>
</ul>
