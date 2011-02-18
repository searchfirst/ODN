<?php if($currentUser): ?>
<div id="user_details">
<p><?php echo $this->Html->link($currentUser['User']['name'],"/users/view/{$currentUser['User']['id']}")?></p>
<ul class="hook_menu">
<li><?php echo $this->Html->link('Logout',"/users/logout",array('class'=>'modalAJAX'))?></li>
</ul>
</div>
<?php endif ?>
