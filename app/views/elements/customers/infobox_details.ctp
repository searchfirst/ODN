<div class="customer_info infobox">
<h3 class="collapse_hook">Details</h3>
<div class="collapse">
<dl>
<?php if(empty($customer['Customer']['cancelled']) || $customer['Customer']['cancelled']=='0000-00-00 00:00:00'):?>
<dt>Joined:</dt>
<dd><?php echo str_replace(', 00:00','',$time->niceShort($customer['Customer']['joined']))?></dd>
<?php else:?>
<dt>Cancelled:</dt>
<dd><?php echo $time->niceShort($customer['Customer']['cancelled'])?></dd>
<?php endif;?>
<dt>Contact Name</dt>
<dd><?php echo $customer['Customer']['contact_name'];?></dd>
<dt>Telephone:</dt>
<dd><?php
echo implode('<br />',explode(';',$customer['Customer']['telephone']));
?></dd>
<?php if(!empty($customer['Customer']['fax'])):?>
<dt>Fax:</dt>
<dd><?php echo $customer['Customer']['fax']?></dd>
<?php endif;?>
<?php if(!empty($customer['Customer']['email'])):?>
<dt>Email:</dt>
<dd><?php
$email_list = explode(';',$customer['Customer']['email']);
foreach($email_list as $i=>$email_item)
$email_list[$i] = $html->link($email_item,'mailto:'.$email_item);
echo implode('<br />',$email_list);
?></dd>
<?php endif;?>
<dt>Address:</dt>
<dd>
<?php echo nl2br(trim(preg_replace('/\n+/',"\n","{$customer['Customer']['address']}\n{$customer['Customer']['town']}\n{$customer['Customer']['county']}\n{$customer['Customer']['post_code']}\n{$customer['Customer']['country']}")));?>
</dd>
</dl>
</div>
</div>