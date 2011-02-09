<section class="invoice detail list">
<h1>Invoices</h1>
<ul class="hook_menu">
<li><?php echo $this->Html->link('Raise Invoice',"/invoices/raise?customer_id={$customer['Customer']['id']}",array('class'=>'add modalAJAX'));?></li>
</ul>
<?php foreach ($customer['Invoice'] as $invoice): ?>
<!--div<?php if(!empty($invoice['service_id'])) echo " class=\"highlight_service_{$invoice['service_id']}\""; ?>-->
<article class="<?php echo implode(' ',array(
	$status->getLcDueString(array('Invoice'=>$invoice)),
	(!empty($invoice['service_id'])?"highlight_service_{$invoice['service_id']}":'')
));?>">
<h1><?php echo $this->Html->link(($invoice['reference']!=''?$invoice['reference']:'Invalid Ref No.'),"/invoices/view/{$invoice['id']}?width=600&amp;height=400",array('class'=>'modalAJAX')) ?></h1>
<ul class="hook_menu">
<li><?php echo $this->Html->link('Generate Invoice [PDF]',"/pdf/invoices/view/{$invoice['id']}",array('class'=>'i_pdf')) ?></li>
<?php if($invoice['date_invoice_paid']):?>
<li><?php echo $this->Html->link('Generate Paid Invoice [PDF]',"/pdf/invoices/view/{$invoice['id']}?AdditionalInformation=Paid%20In%20Full",array('class'=>'i_pdf')) ?></li>
<?php else: ?>
<li><?php echo $this->Html->link('Invoice Paid',"/invoices/paid_in_full/{$invoice['id']}?width=600&amp;height=150",array('class'=>'modalAJAX')) ?></li>
<li><?php echo $this->Html->link('Cancel',"/invoices/cancel/{$invoice['id']}?width=600&amp;height=150",array('class'=>'modalAJAX')) ?></li>
<?php endif ?>
</ul>
<p><?php echo $this->Invoice->invoiceSummary($invoice) ?></p>
</article>
<?php endforeach ?>
</section>
