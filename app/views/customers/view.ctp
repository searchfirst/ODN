<h2 class="<?php
echo Inflector::underscore($status->customerStatus($customer['Customer']['status']));
//echo " highlight_customer_{$customer['Customer']['id']}";
?>"><?php if(!empty($customer['Reseller']['id'])) echo $html->link($customer['Reseller']['company_name'],"/customers/view/{$customer['Reseller']['id']}").' - ' ?><?php echo $customer['Customer']['company_name']?></h2>
<ul class="hook_menu">
<li><?php echo $html->link("Edit Customer","/customers/edit/{$customer['Customer']['id']}",array('class'=>'edit modalAJAX'));?></li>
<li><?php echo $html->link('Add Note',"/ajax/notes/add?customer_id={$customer['Customer']['id']}",array('class'=>'add modalAJAX')) ?></li>
<?php if(!empty($customer['Referral'])):?>
<li><?php echo $html->link('View Customers',"/customers/resellers/{$customer['Customer']['id']}",array('class'=>'modalAJAX'));?></li>
<?php endif;?>
</ul>
<div class="customer_display">

<div class="note_list">
<ul class="note_list">
<?php foreach($customer['Note'] as $i=>$note):?>
<li class="<?php
echo ($i % 2)?'odd':'even';
echo " ";
if($note['flagged']) echo "flagged ";
$model = strtolower($note['model']);
$model_id = $note[$model.'_id'];
echo "highlight_{$model}_{$model_id}";
?>">
<h3><?php echo $note['User']['name']?> (<?php echo $time->niceShort($note['created'])?>)</h3>
<?php
$cud = $current_user['User']['id'];
if($cud==$note['User']['id'] || $cud==$note['Service']['user_id']):?>
<ul class="hook_menu">
<?php if($note['flagged']):?>
<li><?php echo $html->link("Unflag","/notes/unflag/{$note['id']}",array('class'=>'modalAJAX')) ?></li>
<?php else:?>
<li><?php echo $html->link("Flag","/notes/flag/{$note['id']}",array('class'=>'modalAJAX')) ?></li>
<?php endif;?>
</ul>
<?php endif; ?>
<?php echo $textAssistant->htmlFormatted($note['description']) ?> 
</li>
<?php endforeach;?>
</ul>
</div>

<div class="customer_infoboxes">
<?php echo $this->element('customers/infobox_details');?> 
<?php echo $this->element('customers/infobox_services');?> 
<?php echo $this->element('customers/infobox_invoices');?>
</div>
</div>