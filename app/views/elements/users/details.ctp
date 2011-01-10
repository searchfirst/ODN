<?php if(isset($current_user)):?>
<div id="user_details">
<p><?php echo $textAssistant->link($current_user['User']['name'],"/users/view/{$current_user['User']['id']}")?></p>
<ul class="hook_menu">
<li><?php echo $textAssistant->link('Logout',"/users/logout",array('class'=>'modalAJAX'))?></li>
</ul>
</div>
<?php endif;?>