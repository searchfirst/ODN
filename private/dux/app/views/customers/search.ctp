<h2>Customers</h2>
<div class="item_list">
<?php foreach ($customers as $i=>$customer):?>
<div class="item<?php echo $i%2?" even":"" ?>">
<h3><?php echo $html->link($customer['Customer']['company_name'],"/customers/view/{$customer['Customer']['id']}") ?></h3> 
</div>
<?php endforeach?>
</div>