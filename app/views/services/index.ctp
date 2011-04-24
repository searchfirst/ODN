<h2>Services</h2>
<ul class="item_list">
<?php foreach ($services as $i=>$services): ?>
<li><?php echo $this->Html->link($services['Service']['title'],"/services/view/{$services['Service']['id']}") ?></li>
<?php endforeach ?>
</ul>
