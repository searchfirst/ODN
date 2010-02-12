<h2>Customers</h2>
<form method="post" action="<?php print $html->url('/customers/add')?>">
<?php print $form->submit('New Customer',array('div'=>false))?>
</form>
<div class="item_list">
<?php foreach ($customers as $i=>$customer):?>
<div class="item<?php echo $i%2?" even":"" ?>">
<div class="options">
<?php echo $this->renderElement('edit_form',array('id'=>$customer['Customer']['id'],'title'=>$customer['Customer']['company_name']))?> 
<?php echo $this->renderElement('delete_form',array('id'=>$customer['Customer']['id'],'title'=>$customer['Customer']['company_name']))?> 
</div>
<h3><?php echo $html->link($customer['Customer']['company_name'],"/customers/view/{$customer['Customer']['id']}") ?></h3> 
<div class="more_info">
<p>Websites: <?php echo count($customer['Website']) ?></p>
</div>
</div>
<?php endforeach; ?>
</div>