<h1><?php echo $this->Customer->flag($customer['Customer']) ?> <?php echo $customer['Customer']['company_name'] ?>
<?php if (!empty($customer['Reseller']['id'])): ?>
<span class="referrer">Reseller: <?php echo $this->Html->link($customer['Reseller']['company_name'],array('action'=>'view',$customer['Reseller']['id'])) ?></span>
<?php endif ?>
</h1>
<p><?php echo $this->Html->link('Edit Customer', array('action' => 'edit', $customer['Customer']['id'])) ?></p>
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
