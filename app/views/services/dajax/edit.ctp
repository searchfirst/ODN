<h2>Edit Service: <?php echo $service['Customer']['company_name'].'/'.$service['Service']['title'] ?></h2>
<form action="<?php echo $html->url("/services/edit/{$service['Service']['id']}")?>" method="post" accept-charset="UTF-8">
<fieldset>
<legend>Service Details</legend>
<?php echo $form->input('Service.description', array('rows'=>7,'cols'=>70,'label'=>array('text'=>'Details','title'=>'Depending on the service (SEO, design, hosting) give specific details regarding keywords, design requirements, and anything else relating to the service.')))?> 
<?php echo $form->input('Service.user_id', array('options'=>$user,'empty'=>true,'label'=>'Technician','error'=>'Please select a technician for this service'))?> 
<?php echo $form->input('Service.status', array('options'=>$service_status,'selected'=>$service['Service']['status']))?>
<?php echo $form->input('Service.schedule', array('options'=>$service_schedule))?>
</fieldset>
<?php if(!empty($service['Services']['website_id'])) echo $form->hidden('Service.website_id',$service['Service']['website_id'])?>
<?php if(!empty($service['Service']['customer_id'])):?>
<?php echo $form->hidden('Service.customer_id')?> 
<?php echo $form->hidden('Service.id')?> 
<?php endif;?>
<?php echo $form->submit('Save Changes')?> 
</form>