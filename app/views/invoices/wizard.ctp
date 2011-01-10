<h2>Invoice Report Wizard</h2>
<div class="formcolumns">
<?php echo $form->create(null);?> 
<p>Generate a report for a specific month</p>
<?php echo $form->input('date',array(
	'type'=>'date',
	'dateFormat' => 'MY',
	'minYear' => 2000,
	'maxYear' => date('Y')
));?> 
<?php echo $form->input('types',array('label'=>'Type'));?> 
<?php echo $form->end('Apply');?>
<?php echo $form->create(null);?> 
<p>Generate a report between 2 dates</p>
<?php echo $form->input('start_date',array(
	'type'=>'date',
	'dateFormat' => 'DMY',
	'minYear' => 2000,
	'maxYear' => date('Y')
));?> 
<?php echo $form->input('end_date',array(
	'type'=>'date',
	'dateFormat' => 'DMY',
	'minYear' => 2000,
	'maxYear' => date('Y')
));?> 
<?php echo $form->input('types',array('label'=>'Type'));?> 
<?php echo $form->end('Apply');?>
</div>
<?php if(!empty($invoices)):?>
<table>
<thead><tr>
<th>Invoice No.</th>
<th>Company</th>
<th>Amount</th>
<th>Created</th>
<th>Paid</th>
</tr></thead>
<tfoot><tr>

</tr></tfoot>
<tbody>
<?php foreach($invoices as $invoice):?>
<tr><?php
echo sprintf('<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>',
	$html->link($invoice['Invoice']['reference'],"/invoices/view/{$invoice['Invoice']['id']}"),
	$html->link($invoice['Customer']['company_name'],"/customers/view/{$invoice['Customer']['id']}"),
	money_format('%.2n',$invoice['Invoice']['amount']),
	$time->format('d-m-Y',$invoice['Invoice']['created']),
	$invoice['Invoice']['date_invoice_paid']?$time->format('d-m-Y',$invoice['Invoice']['date_invoice_paid']):'Unpaid'
);
?></tr>
<?php endforeach;?>
</tbody>
</table>
<?php endif;?>