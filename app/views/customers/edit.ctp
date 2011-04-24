<h1>Edit Customer</h1>
<?php echo $this->Form->create('Customer') ?> 
<?php echo $this->Form->input('Customer.company_name',array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Company Name.'))?> 
<?php if (!empty($customers)): ?>
<?php echo $this->Form->input('Customer.customer_id',array('label'=>'Reseller','empty'=>true)) ?>
<?php endif ?>
<?php echo $this->Form->input('Customer.id')?>
<?php echo $this->Form->end('Save Changes') ?>
</form>
