<?php if($currentUser): ?>
<div id="user_details">
<ul>
<li><?php echo $this->Html->link($currentUser['User']['name'],"/users/view/{$currentUser['User']['id']}")?> </li>
<li><?php echo $this->Html->link('Logout',"/users/logout",array('class'=>'modalAJAX'))?></li>
</ul>
</div>
<?php endif ?>
