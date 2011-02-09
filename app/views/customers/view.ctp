<h1>
<?php if (!$this->Customer->isActive($customer['Customer']['status'])): ?>
<?php echo $this->Customer->flagTag($customer['Customer']['status']) ?>
<?php endif ?>
<?php echo $customer['Customer']['company_name']?>
<?php if(!empty($customer['Reseller']['id'])):?>
<span class="referrer">Reseller/Referrer: <?php echo $this->Html->link($customer['Reseller']['company_name'],"/customers/view/{$customer['Reseller']['id']}"); ?></span>
<?php endif; ?>
</h1>
<ul class="hook_menu">
<li><?php echo $this->Html->link("Edit Customer","/customers/edit/{$customer['Customer']['id']}",array('class'=>'edit modalAJAX'));?></li>
<li><?php echo $this->Html->link('Add Note',"/ajax/notes/add?customer_id={$customer['Customer']['id']}",array('class'=>'add modalAJAX')) ?></li>
</ul>
<p>Customer since <?php echo $this->Time->niceShort($customer['Customer']['joined']) ?> 
<div class="cwrap_2">
<div>
<?php echo $this->element('customers/details') ?> 
<?php echo $this->element('customers/customers') ?> 
<?php echo $this->element('customers/services') ?> 
<?php echo $this->element('customers/invoices') ?> 
</div>
<div>
<?php echo $this->element('customers/notes') ?> 
</div>
</div>
