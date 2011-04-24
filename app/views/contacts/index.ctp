<h2>Contacts</h2>
<ul class="item_list">
<?php foreach ($contacts as $i=>$contact):?>
<li><?php echo $html->link($contact['Contact']['name'],"/contacts/view/{$contact['Contact']['id']}") ?></li>
<?php endforeach?>
</ul>
