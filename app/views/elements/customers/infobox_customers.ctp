<?php if(!empty($customer['Referral'])): ?>
<div class="customer_info infobox">
<h3 class="collapse_hook">Customers</h3>
<!--ul class="hook_menu">
<li><?php echo $html->link('Add Customer',"/customers/add?data[Referral][id]={$customer['Customer']['id']}",array('class'=>'add modalAJAX'));?></li>
</ul-->

<div class="collapse">
<ul class="tab_hooks">
<li><a href="#active_customers">Active</a></li>
<li><a href="#cancelled_customers">Cancelled</a></li>
</ul>
<div class="tab_page">
<ul class="item_list">
<?php foreach($customer['Referral'] as $rcustomer):?>
<?php if($rcustomer['status']!=Customer::$status['Cancelled']):?>
<li class="<?php Inflector::underscore($customer_status_numbers[$rcustomer['status']]); ?>"><a href="/customers/view/<?php echo $rcustomer['id'];?>"><?php echo $rcustomer['company_name'];?></a></li>
<?php endif;?>
<?php endforeach;?>
</ul>
</div>
<div class="tab_page">
<ul class="item_list">
<?php foreach($customer['Referral'] as $rcustomer):?>
<?php if($status->getStatusString('Customer',$rcustomer['status']) == 'Cancelled'):?>
<li><a href="/customers/view/<?php echo $rcustomer['id'];?>"><?php echo $rcustomer['company_name'];?></a></li>
<?php endif;?>
<?php endforeach;?>
</ul>
</div>

</div>
</div>
<?php endif; ?>