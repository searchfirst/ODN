<h1><?php echo $contact['Contact']['name']?></h1>
<ul class="hook_menu">
<li><?php echo $this->Html->link("Edit Contact","/contacts/edit/{$contact['Contact']['id']}",array('class'=>'edit modalAJAX')) ?></li>
<li><?php echo $this->Html->link("Delete Contact","/contacts/edit/{$contact['Contact']['id']}",array('class'=>'edit modalAJAX')) ?></li>
</ul>
<?php echo $this->T->format(array('text'=>$this->Contact->fullSummary($contact))) ?>
