<h1>
<?php if (!$this->Customer->isActive($customer['Customer'])): ?>
<?php echo $this->Customer->flagTag($customer['Customer']) ?>
<?php endif ?>
<?php echo $customer['Customer']['company_name'] ?>
<?php if (!empty($customer['Reseller']['id'])): ?>
<span class="referrer">Reseller: <?php echo $this->Html->link($customer['Reseller']['company_name'],array('action'=>'view',$customer['Reseller']['id'])) ?></span>
<?php endif ?>
</h1>
<ul class="hook_menu">
<li><?php echo $this->Html->link("Edit Customer","/customers/edit/{$customer['Customer']['id']}",array('class'=>'edit modalAJAX'));?></li>
</ul>
<p>Customer since <?php echo $this->Time->niceShort($customer['Customer']['joined']) ?> 
<div class="fwrap c2 p">
<div>
<?php echo $this->element('customers/details') ?> 
<?php echo $this->element('customers/services') ?> 
<?php echo $this->element('customers/invoices') ?> 
<?php echo $this->element('customers/customers') ?> 
</div>
<div>
<?php echo $this->element('customers/notes') ?> 
</div>
</div>
