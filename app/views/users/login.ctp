<h1>Login</h1>
<?php echo $this->Form->create('User', array('url'=>array('controller'=>'users','action'=>'login'))) ?>
<?php echo $this->Form->input('User.email') ?>
<?php echo $this->Form->input('User.password') ?>
<?php echo $this->Form->end('Login') ?>
