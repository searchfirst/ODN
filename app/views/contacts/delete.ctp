<p>Do you really wish to delete this contact?</p>
<?php echo $this->Form->create('Contact',array('action'=>'delete',$id)) ?> 
<?php echo $this->Form->input('id') ?> 
<?php echo $this->Form->end(__('Yes, delete this contact',true)) ?> 
