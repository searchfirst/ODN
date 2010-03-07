<h2 class="<?php
echo strtolower($status->customerStatus($customer['Customer']['status']));
echo " highlight_customer_{$customer['Customer']['id']}";
?>"><?php if(!empty($customer['Reseller']['id'])) echo $html->link($customer['Reseller']['company_name'],"/customers/view/{$customer['Reseller']['id']}").' - ' ?><?php echo $customer['Customer']['company_name']?></h2>
<div class="options">
<?php echo $this->renderElement('edit_form',array('id'=>$customer['Customer']['id'],'title'=>$customer['Customer']['company_name']))?> 
</div>

<div id="item_display">
<ul>
<li><a href="#item_display_information">Information</a></li>
<?php if(!empty($customer['Referral'])):?>
<li><a href="#item_display_reseller">Customers</a></li>
<?php endif;?>
</ul>

<div id="item_display_information">
<h3 class="tabs-heading">Information</h3>

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

</div>
</div>

<div class="service_info infobox">
<div class="options">
<?php echo $this->renderElement('new_item_form',array(
	'parentClass'=>'Customer','parentName'=>$customer['Customer']['company_name'],'parentId'=>$customer['Customer']['id'],'model'=>'Service','controller'=>'Services'))?> 
<?php echo $this->renderElement('new_item_form',array(
	'parentClass'=>'Customer','parentName'=>$customer['Customer']['company_name'],'parentId'=>$customer['Customer']['id'],'model'=>'Website','controller'=>'Websites'))?> 
</div>
<h3>Services</h3>
<table class="item_list">
<thead>
<tr><th>Service</th><th>Technician</th><th>Schedule</th><th>Status</th><th>Signup/Cancel</th></tr>
</thead>
<tbody>
<?php $x_website = '';?>
<?php foreach($customer['Service'] as $service):?>
<?php if($x_website!=$service['Website']['uri']):?>
<tr><th colspan="5"><?php echo $html->link($service['Website']['uri'],"/ajax/websites/view/{$service['Website']['id']}?width=400;height=200",array('class'=>'thickbox'));?><?php
if(!empty($service['Website']['aliases'])){
echo ' <small>('.$service['Website']['aliases'].')</small>';
}
?></th></tr>
<?php $x_website = $service['Website']['uri']; endif; ?>
<tr class="<?php
echo strtolower($status->serviceStatus($service['status']));
echo " highlight_service_".$service['id'];
?>">
<td><?php echo $html->link($service['title'], "/ajax/services/view/{$service['id']}?width=600;height=400",array('class'=>'thickbox'))?></td>
<td><?php echo $service['User']['name'] ?></td>
<td><?php echo $status->serviceSchedule($service['schedule']);?></td>
<td><?php echo $status->serviceStatus($service['status']) ?></td>
<td><?php echo str_replace(', 00:00','',$time->niceShort($service['joined']));
if($service['cancelled']) echo '/'.str_replace(', 00:00','',$time->niceShort($service['cancelled']));?></td>
</tr>
<?php endforeach;?>
</tbody>
</table>
<?php if(empty($customer['Service'])):?>
<p>No Services</p>
<?php endif;?>
</div>

<div class="invoice_info infobox">
<div class="options">
<?php //echo $this->renderElement('new_item_form_ajax',array(
	//'parentClass'=>'Customer','parentName'=>$customer['Customer']['company_name'],'parentId'=>$customer['Customer']['id'],'model'=>'Invoice','controller'=>'Invoices'))?> 
<a href="<?php echo $html->url('/ajax/invoices/raise') ?>?width=600&amp;height=400&amp;customer_id=<?php echo $customer['Customer']['id'] ?>" class="add button thickbox">Raise Invoice</a>
</div>
<h3>Last 5 Invoices</h3>
<table class="item_list">
<thead>
<tr><th>Invoice No.</th><th>Amount</th><th>Issue Date</th><th>Due Date</th></tr>
</thead>
<tbody>
<?php foreach ($customer['Invoice'] as $invoice): ?>
<tr>
<td><ul class="jd_menu"><li><?php echo $html->link(($invoice['reference']!=''?$invoice['reference']:'Invalid Ref No.'),"/ajax/invoices/view/{$invoice['id']}?width=600&amp;height=400",array('class'=>'thickbox')) ?>
<ul>
<li><?php echo $html->link('Generate Invoice [PDF]',"/pdf/invoices/view/{$invoice['id']}",array('class'=>'i_pdf')) ?></li>
<?php if($invoice['date_invoice_paid']):?>
<li><?php echo $html->link('Generate Paid Invoice [PDF]',"/pdf/invoices/view/{$invoice['id']}?AdditionalInformation=Paid%20In%20Full",array('class'=>'i_pdf')) ?></li>
<?php else: ?>
<li><?php echo $html->link('Invoice Paid',"/ajax/invoices/paid_in_full/{$invoice['id']}?width=600&amp;height=150",array('class'=>'thickbox')) ?></li>
<?php endif;?>
</ul>
</li></ul></td>
<td><?php echo money_format('%n',$invoice['amount']) ?></td>
<td><?php echo $time->niceShort($invoice['created']) ?></td>
<td><?php echo $time->niceShort($invoice['due_date']) ?></td>
</tr>
<?php endforeach ?>
</tbody>
</table>
</div>

<div class="note_list">

<div class="options"><form method="post" action="/notes/add" class="model_command">
<button name="data[Customer][submit]" value="" class="thickbox" alt="/ajax/notes/add?customer_id=<?php echo $customer['Customer']['id']?>" title="Add Note">
<img src="/img/new-item-icon.png" alt="" />
Add Note</button>
<input type="hidden" name="data[Referrer][customer_id]" value="<?php echo $customer['Customer']['id'] ?>" />
</form>
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
</div>

<div id="item_display_reseller">
<h3 class="tabs-heading">Reseller Customers</h3>
<ul class="item_list">
<?php foreach ($customer['Referral'] as $i=>$referral):?>
<li class="item <?php echo Inflector::underscore($status->customerStatus($referral['status'])) ?>"><?php echo $html->link($referral['company_name'],"/customers/view/{$referral['id']}") ?></li>
<?php endforeach; ?>
</ul>
</div>

</div>
</div>