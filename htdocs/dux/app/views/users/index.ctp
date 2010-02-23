<h2>Employees</h2>
<form method="post" action="<?php print $html->url('/users/add')?>">
<?php echo $form->submit('New Employee',array('div'=>false))?>
</form>
<div class="item_list">
<?php foreach ($users as $i=>$user):?>
<div class="item<?php echo $i%2?" even":"" ?>">
<div class="options">
<?php echo $this->renderElement('edit_form',array('id'=>$user['User']['id'],'title'=>$user['User']['name']))?> 
<?php echo $this->renderElement('delete_form',array('id'=>$user['User']['id'],'title'=>$user['User']['name']))?> 
</div>
<h3><?php echo $html->link($user['User']['name'],"/users/view/{$user['User']['id']}") ?></h3> 
<div class="more_info">
<p>Customers: <?php echo count($user['Customer']) ?></p>
</div>
</div>
<?php endforeach; ?>
</div>