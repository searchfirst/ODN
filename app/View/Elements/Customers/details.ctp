<section class="contact detail list">
<h1>Contacts</h1>
<ul class="hook_menu">
<li><?php echo $this->Html->link('Add Contact', array(
    'controller'=>'contacts','action'=>'add','?'=>array('data[Customer][id]'=>$customer['Customer']['id'])
)) ?></li>
</ul>
<?php foreach ($customer['Contact'] as $contact): ?>
<article>
<h1><?php echo $contact['name'].(!empty($contact['role']) ? " - {$contact['role']}" : '') ?></h1>
<ul class="hook_menu">
<li><?php echo $this->Html->link('Edit Contact', array('controller'=>'contacts','action'=>'edit',$contact['id']),array('class'=>'modalAJAX')) ?></li>
<li><?php echo $this->Html->link('Delete Contact',array('controller'=>'contacts','action'=>'delete',$contact['id']),array('class'=>'modalAJAX')) ?></li>
</ul>
<?php echo $this->Text->format(array('text'=>$this->Contact->fullSummary($contact))) ?> 
</article>
<?php endforeach ?>
</section>

