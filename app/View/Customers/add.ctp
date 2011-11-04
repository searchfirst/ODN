<h1>New Customer</h1>
<?php echo $this->Form->create('Customer') ?> 
<fieldset><legend>Company Details</legend>
<?php echo $this->Form->input('company_name') ?> 
<?php if (!empty($this->data['Customer']['customer_id'])): ?>
<?php echo $this->Form->hidden('customer_id') ?> 
<?php else: ?>
<?php echo $this->Form->input('customer_id',array('label'=>__('Customer Of',true),'empty'=>true)) ?> 
<?php endif ?>
</fieldset>
<fieldset><legend>Contact Info</legend>
<?php echo $this->Form->input('Contact.0.name') ?> 
<?php echo $this->Form->input('Contact.0.role',array('value'=>'Primary Contact')) ?> 
<?php echo $this->Form->input('Contact.0.email') ?> 
<?php echo $this->Form->input('Contact.0.telephone') ?> 
<?php echo $this->Form->input('Contact.0.fax') ?> 
<?php echo $this->Form->input('Contact.0.address') ?> 
</fieldset>
<fieldset><legend>Website</legend>
<?php echo $this->Form->input('Website.0.uri',array('label'=>'Web Address'))?> 
</fieldset>
<?php echo $this->Form->end('Add Customer') ?> 
