<h2>Customers</h2>
<ul class="item_list">
<?php foreach ($customers as $i=>$customer):?>
<li class="<?php echo $i%2?"even":"odd" ?>"><?php echo $html->link($customer['Customer']['company_name'],"/customers/view/{$customer['Customer']['id']}") ?></li>
<?php endforeach?>
</ul>