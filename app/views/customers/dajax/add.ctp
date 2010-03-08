<form action="<?php echo $html->url('/customers/add'); ?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
<fieldset> 
<legend>Company Details</legend>
<?php echo $form->hidden('Customer.id')?> 
<?php echo $form->input('Customer.company_name',array('size'=>'30','maxlength'=>'150','error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.contact_name',array('size'=>'30','maxlength'=>'150','error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.email',array('size'=>'30','maxlength'=>'150','error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.telephone',array('size'=>'20','maxlength'=>'20','error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.fax',array('size'=>'30','maxlength'=>'150','error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.address',array('cols'=>40,'rows'=>3,'error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.town',array('size'=>'30','maxlength'=>'50','error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.county',array('size'=>'30','maxlength'=>'50','error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.post_code',array('size'=>'10','maxlength'=>'10','error'=>'Please enter the Company Name.'))?> 
</fieldset>
<fieldset>
<legend>Website</legend>
<?php echo $form->input('Website.uri',array('size'=>'40','maxlength'=>'150','label'=>'Web Address','error'=>'Web Address needed.'))?> 
</fieldset>
<?php if(!empty($customer_list) && empty($customer['Referral']) && !empty($customer['Customer'])):?>
<fieldset>
<legend>Reseller</legend>
<?php echo $form->input('Customer.customer_id',array('options'=>$customer_list,'empty'=>true,'selected'=>$customer['Customer']['customer_id'],'label'=>'Customer Of','error'=>"Your customer can't be a customer of itself."))?> 
</fieldset>
<?php endif;?>
<?php echo $form->submit('Add',array('div'=>false));?> 
</fieldset>
</form>