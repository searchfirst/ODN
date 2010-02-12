<h2>Authenticate</h2>
<p>You must be logged in before you can access this area.</p>

<form method="post" action="/users/authenticate">
<?php echo $form->input('User.email')?> 
<?php echo $form->input('User.password')?> 
<?php echo $form->submit('Authenticate')?> 
</form>