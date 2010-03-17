<?php if(isset($resellers)):?>
<h2>Resellers</h2>
<ul class="item_list">
<?php foreach($resellers as $i=>$reseller):?>
<li class="item<?php echo $i%2?" even":""; ?>"><a href="/customers/view/<?php echo $reseller['Customer']['id'];?>"><?php echo $reseller['Customer']['company_name'];?></a></li>
<?php endforeach;?>
</ul>
<?php elseif(isset($reseller)):?>
<h2>Customers of <?php echo $reseller['Customer']['company_name'];?></h2>
<ul class="item_list">
<?php // print_r($reseller);?>
<?php foreach($reseller['Referral'] as $i=>$customer):?>
<li class="<?php echo ($i%2?"even ":"odd ").Inflector::underscore($customer_status_numbers[$customer['status']]); ?>"><a href="/customers/view/<?php echo $customer['id'];?>"><?php echo $customer['company_name'];?></a></li>
<?php endforeach;?>
</ul>
<?php endif;?>