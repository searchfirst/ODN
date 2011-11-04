<h2>Add Service</h2>
<form action="<?php echo $html->url('/services/add')?>" method="post" accept-charset="UTF-8">
<fieldset>
<legend>Service Details</legend>
<?php echo $form->input('Service.title', array('options'=>am($service_titles,array('other'=>'Other')),'empty'=>true,'label'=>'Service Type'))?> 
<input type="text" name="data[Service][Title]">
<?php echo $form->input('Service.description', array('rows'=>20,'cols'=>70,'label'=>array('text'=>'Details','title'=>'Depending on the service (SEO, design, hosting) give specific details regarding keywords, design requirements, and anything else relating to the service.')))?> 
<?php echo $form->input('Service.user_id', array('options'=>$user,'empty'=>true,'label'=>'Employee'))?> 
<?php echo $form->input('Service.website_id', array('options'=>$website,'empty'=>true,'label'=>'Website'))?> 
<?php echo $form->input('Service.status', array('options'=>$service_status,'selected'=>SERVICE_STATUS_ACTIVE))?>
<?php echo $form->input('Service.schedule', array('options'=>$service_schedule))?>
</fieldset>
<?php echo $form->hidden('Service.customer_id')?> 
<?php echo $form->submit('Add')?> 
</form>