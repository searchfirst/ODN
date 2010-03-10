<div class="invoice_info infobox">
<div class="options">
<?php echo $html->link('Raise Invoice',"/ajax/invoices/raise?customer_id={$customer['Customer']['id']}",array('class'=>'add button modalAJAX'));?>
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
<li><?php echo $html->link('Invoice Paid',"/ajax/invoices/paid_in_full/{$invoice['id']}?width=600&amp;height=150",array('class'=>'modalAJAX')) ?></li>
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