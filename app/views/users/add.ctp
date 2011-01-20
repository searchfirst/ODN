<h1>Users</h1>
<?php echo $this->Form->create('User');?>
<fieldset><legend><?php __('Add User'); ?></legend>
<?php
echo $this->Form->input('email');
echo $this->Form->input('password');
echo $this->Form->input('group_id',array('options'=>$group_ids));
?>
</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
<ul>
<li><?php echo $this->Html->link(__('List Users', true), array('action' => 'index'));?></li>
</ul>
