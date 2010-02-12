<h2>Login</h2>
<form method="post" action="<?php echo $html->url('/user/login')?>">
<?php echo $form->input('User.email',array('label'=>'Email Address','error'=>'You must provide a username'))?>
<?php echo $form->input('User.password',array('error'=>'You must provide your password'))?>
<?php echo $form->submit('Login')?>
</form>