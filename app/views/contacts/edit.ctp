<h1>New Customer</h1>
<?php echo $this->Form->create('Contact') ?> 
<fieldset><legend>Contact Details</legend>
<?php echo $this->Form->input('name') ?> 
<?php echo $this->Form->input('role') ?> 
<?php echo $this->Form->input('email',array('type'=>'email')) ?> 
<?php echo $this->Form->input('telephone',array('type'=>'tel')) ?> 
<?php echo $this->Form->input('mobile',array('type'=>'tel')) ?> 
<?php echo $this->Form->input('fax',array('type'=>'tel')) ?> 
<?php echo $this->Form->input('address') ?> 
</fieldset>
<?php echo $this->Form->input('Customer.id') ?> 
<?php echo $this->Form->input('id') ?> 
<?php echo $this->Form->end('Save Changes') ?>

