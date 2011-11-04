<h1>Contacts</h1>
<ul class="item_list">
<?php foreach ($contacts as $contact): ?>
<li><?php echo $this->Html->link($contact['Contact']['name'],"/contacts/view/{$contact['Contact']['id']}") ?></li>
<?php endforeach ?>
</ul>
