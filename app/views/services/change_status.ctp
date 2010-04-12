<form method="post" action="<?php echo $this->webroot?>services/change_status/<?php echo $service['Service']['id']?>">
<fieldset><legend>Change Service Status</legend>
<?php echo $form->input('Service.status',array(
	'options'=>array(2=>'Active',0=>'Cancelled',1=>'Pending',3=>'Complete'),
	'selected'=>$service['Service']['status']
));?> 
<?php echo $form->input('Service.cancelled',array('disabled'=>'disabled','div'=>array('id'=>'ServiceCancelled')));?> 
<?php echo $form->input('Note.description',array('rows'=>5,'cols'=>20));?> 
<?php echo $form->hidden('Note.model',array('value'=>'Service'));?> 
<?php echo $form->hidden('Note.customer_id',array('value'=>$service['Service']['customer_id']));?> 
<?php echo $form->hidden('Note.service_id',array('value'=>$service['Service']['id']));?> 
<?php echo $form->submit();?>
</fieldset>
</form>