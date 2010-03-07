<form action="<?php echo $html->url('/invoices/raise'); ?>" method="post" accept-charset="UTF-8" id="invoice_raise">
<fieldset> 
<legend>Raise Invoice</legend>
<?php echo $form->input('Invoice.description',array(
	'type'=>'textarea','cols'=>'30','rows'=>'5','error'=>'Please enter the Description.'))?> 
<?php echo $form->input('Invoice.reference',array('value'=>$generated_invoice_reference,'error'=>'Please give a valid reference.'))?> 
<?php echo $form->input('Invoice.your_reference',array('label'=>'Their Reference','error'=>'Please give a valid reference.'))?> 
<?php echo $form->input('Invoice.amount',array('error'=>'Please give the total.'))?> 
<?php echo $form->input('Invoice.vat_included',array('checked'=>'checked')) ?>
<?php echo $form->input('Invoice.due_date',array('error'=>'Please give the due date.','div'=>array('class'=>'inline')))?> 
<?php echo $form->input('Invoice.next_invoice_due',array('error'=>'Please give the due date.','div'=>array('class'=>'inline')))?> 
<?php //echo $form->input('Service.schedule', array('options'=>$service_schedule))?>
<?php echo $form->input('Invoice.service_id',array('options'=>$services,'label'=>'Service','div'=>array('class'=>'inline'))) ?>
</fieldset>
<?php if(!empty($invoice['Invoice']['customer_id'])) echo $form->hidden('Invoice.customer_id',array('value'=>$invoice['Invoice']['customer_id']));?> 
<?php echo $form->submit('Add')?> 
<?php // echo $form->submit('Add',array('label'=>false,'div'=>false))?> 
</form>
<div id="table_preview"></div>
<script type="text/javascript">
$('#invoice_raise *').bind('change',function(e){
    $("#table_preview").html("<img src='/js/jquery/thickbox/loadingAnimation.gif' alt='Loading...' />");
	var invoice_summary = $('#invoice_raise textarea').val();
	var invoice_amount = $('#invoice_raise #InvoiceAmount').val();
	var invoice_vat_enabled = $('#invoice_raise #InvoiceVatIncluded').attr('checked');
	if(invoice_vat_enabled) {
		$invoice_totals = "VAT Total," + (0.175 * invoice_amount) + "\n" + "Grand Total," + (1.175 * invoice_amount);
	} else {
		
	}
	var invoice_csv = "<strong>Detail</strong>,<strong>Cost</strong>\n" + invoice_summary;
	var final_csv = $.csv()(invoice_csv);
	$("#table_preview").table({replace:false,data:final_csv});
});
</script>