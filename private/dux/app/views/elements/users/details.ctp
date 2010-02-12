<?php if(isset($current_user)):?>
<div id="user_details">
<p><?php echo $textAssistant->link($current_user['User']['name'],"/users/view/{$current_user['User']['id']}")?></p>
<form method="post" action="/users/logout">
<?php echo $form->submit('Logout')?>
</form>
</div>
<?php endif;?>