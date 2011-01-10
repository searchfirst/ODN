<h2>Raise New Invoice</h2>
<form action="<?php echo $html->url('/invoices/raise'); ?>" method="post" accept-charset="UTF-8" id="invoice_raise">
<fieldset> 
<?php echo $form->input('Invoice.description',array(
	'type'=>'textarea','cols'=>'30','rows'=>'5','error'=>'Please enter the Description.'))?> 
<?php echo $form->input('Invoice.reference',array('error'=>'Please give a valid reference.'))?> 
<?php echo $form->input('Invoice.your_reference',array('label'=>'Their Reference','error'=>'Please give a valid reference.'))?> 
<?php echo $form->input('Invoice.amount',array('label'=>'Amount','type'=>'number','error'=>'Please give the total.','min'=>0))?> 
<?php echo $form->input('Invoice.vat_included',array());?> 
<?php echo $form->input('Invoice.due_date',array('error'=>'Please give the due date.','div'=>array('class'=>'inline'),'selected'=>strftime('%Y-%m-%d',strtotime('+28 days'))))?> 
<?php echo $form->input('Invoice.next_invoice_due',array('error'=>'Please give the due date.','div'=>array('class'=>'inline'),'empty'=>true))?> 
<?php //echo $form->input('Service.schedule', array('options'=>$service_schedule))?>
<?php if(!empty($services)):?>
<?php echo $form->input('Invoice.service_id',array('options'=>$services,'label'=>'Service','div'=>array('class'=>'inline'))) ?>
<?php endif ?>
</fieldset>
<?php echo $form->hidden('Invoice.customer_id');?> 
<?php echo $form->submit('Add')?> 
</form>
<div id="table_preview"></div>