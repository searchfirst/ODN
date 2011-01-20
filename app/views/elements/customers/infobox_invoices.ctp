<div class="invoice_info infobox">
<h3>Invoices</h3>
<ul class="hook_menu">
<li><?php echo $html->link('Raise Invoice',"/invoices/raise?customer_id={$customer['Customer']['id']}",array('class'=>'add modalAJAX'));?></li>
</ul>
<?php foreach ($customer['Invoice'] as $invoice): ?>
<!--div<?php if(!empty($invoice['service_id'])) echo " class=\"highlight_service_{$invoice['service_id']}\""; ?>-->
<div class="<?php echo implode(' ',array(
	$status->getLcDueString(array('Invoice'=>$invoice)),
	(!empty($invoice['service_id'])?"highlight_service_{$invoice['service_id']}":'')
));?>">
<h5><?php echo $html->link(($invoice['reference']!=''?$invoice['reference']:'Invalid Ref No.'),"/invoices/view/{$invoice['id']}?width=600&amp;height=400",array('class'=>'modalAJAX')) ?></h5>
<ul class="hook_menu">
<li><?php echo $html->link('Generate Invoice [PDF]',"/pdf/invoices/view/{$invoice['id']}",array('class'=>'i_pdf')) ?></li>
<?php if($invoice['date_invoice_paid']):?>
<li><?php echo $html->link('Generate Paid Invoice [PDF]',"/pdf/invoices/view/{$invoice['id']}?AdditionalInformation=Paid%20In%20Full",array('class'=>'i_pdf')) ?></li>
<?php else:?>
<li><?php echo $html->link('Invoice Paid',"/invoices/paid_in_full/{$invoice['id']}?width=600&amp;height=150",array('class'=>'modalAJAX')) ?></li>
<li><?php echo $html->link('Cancel',"/invoices/cancel/{$invoice['id']}?width=600&amp;height=150",array('class'=>'modalAJAX')) ?></li>
<?php endif;?>
</ul>
<p>
<span class="flags">
<?php if($invoice['cancelled']) echo "<span class=\"cancelled\">Cancelled</span>" ?>
</span>
<?php
if($invoice['date_invoice_paid']) {
	echo "Payment made for ".money_format('%n',$invoice['amount']).". Issued on ".substr($time->niceShort($invoice['created']),0,-7)." and paid on ".substr($time->niceShort($invoice['date_invoice_paid']),0,-7);
} elseif(strtotime($invoice['due_date']) < time()) {
	echo "Payment overdue for ".money_format('%n',$invoice['amount'])." (due ".$time->timeAgoInWords($invoice['due_date'],array('end'=>0))."). Issued on ".substr($time->niceShort($invoice['created']),0,-7)." and due ".substr($time->niceShort($invoice['due_date']),0,-7);
} else {
	echo "Payment due for ".money_format('%n',$invoice['amount'])." by ".substr($time->niceShort($invoice['due_date']),0,-7).". Issued on ".substr($time->niceShort($invoice['created']),0,-7);
}
?></p>
</div>
<?php endforeach;?>
</div>
