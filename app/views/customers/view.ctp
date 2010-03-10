<div class="options">
<?php if(!empty($customer['Referral'])):?>
<?php echo $html->link('View Customers',"/customers/resellers/{$customer['Customer']['id']}",array('class'=>'modalAJAX button'));?>
<?php endif;?>
<?php echo $html->link("Edit Customer","/ajax/customers/edit/{$customer['Customer']['id']}",array('class'=>'edit button modalAJAX'));?>
</div>
<h2 class="<?php
echo Inflector::underscore($status->customerStatus($customer['Customer']['status']));
echo " highlight_customer_{$customer['Customer']['id']}";
?>"><?php if(!empty($customer['Reseller']['id'])) echo $html->link($customer['Reseller']['company_name'],"/customers/view/{$customer['Reseller']['id']}").' - ' ?><?php echo $customer['Customer']['company_name']?></h2>
<div class="customer_display">

<div class="note_list">
<div class="options">
<?php echo $html->link('Add Note',"/ajax/notes/add?customer_id={$customer['Customer']['id']}",array('class'=>'add button modalAJAX')) ?>
</div>
<h3>Notes</h3>
<ul class="note_list">
<?php foreach($customer['Note'] as $i=>$note):?>
<li class="<?php
echo ($i % 2)?'odd':'even';
echo " ";
$model = strtolower($note['model']);
$model_id = $note[$model.'_id'];
echo "highlight_{$model}_{$model_id}";
?>"><span><?php echo $note['User']['name']?> (<?php echo $time->niceShort($note['created'])?>)</span>
<?php echo nl2br($textAssistant->sanitiseText($note['description'])) ?>
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