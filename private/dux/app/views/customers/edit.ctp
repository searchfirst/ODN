<h2>Edit Customer: <?php echo $html->link($customer['Customer']['company_name'],'/customers/view/'.$customer['Customer']['id']) ?></h2>
<form action="<?php echo $html->url("/customers/edit/{$customer['Customer']['id']}")?>" method="post" accept-charset="UTF-8">
<fieldset> 
<legend>Company Details</legend>
<?php echo $form->hidden('Customer.id')?> 
<?php echo $form->input('Customer.company_name',array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.contact_name',array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.email',array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.telephone',array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.fax',array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.address',array('cols'=>40,'rows'=>3,'error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.town',array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.county',array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Company Name.'))?> 
<?php echo $form->input('Customer.post_code',array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Company Name.'))?> 
</fieldset>
<?php if(!empty($customer_list) && empty($customer['Referral'])):?>
<fieldset>
<legend>Reseller</legend>
<?php echo $form->input('Customer.customer_id',array('options'=>$customer_list,'empty'=>true,'selected'=>$customer['Customer']['customer_id'],'label'=>'Customer Of','error'=>"Your customer can't be a customer of itself."))?> 
</fieldset>
<?php endif;?>
<?php echo $form->input('Customer.id')?>
<?php echo $form->submit('Save')?> 
</form>