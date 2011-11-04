<h2>Invoice no. <?php echo $invoice['Invoice']['reference']?></h2>

<?php echo $form->create('Invoice',array('url'=>array("action"=>"edit",$invoice["Invoice"]["id"]))) ?> 
<fieldset>
<legend>Paid ina Full</legend>
<?php echo $form->input('Invoice.date_invoice_paid',array('error'=>'Please give the date paid.','div'=>array('class'=>'inline')))?> 
</fieldset>
<?php if(!empty($invoice['Invoice']['customer_id'])) echo $form->hidden('Invoice.customer_id',array('value'=>$invoice['Invoice']['customer_id']));?> 
<?php echo $form->hidden('Note.customer_id',array('value'=>$invoice['Invoice']['customer_id']));?> 
<?php echo $form->hidden('Note.service_id',array('value'=>$invoice['Invoice']['service_id']));?> 
<?php echo $form->hidden('Note.invoice_id',array('value'=>$invoice['Invoice']['id']));?> 
<?php
global $current_user;
echo $form->hidden('Note.user_id',array('value'=>$current_user['User']['id']));
?> 
<?php echo $form->hidden('Note.description',array('value'=>"Invoice {$invoice['Invoice']['reference']} paid in full"));?>
<?php echo $form->hidden('Note.model',array('value'=>'Service'));?>
<?php echo $form->hidden('Invoice.id',array('value'=>$invoice['Invoice']['id']));?>
<?php echo $form->end('Add') ?> 
</form>
