<h2>Edit Website</h2>
<form action="<?php echo $html->url("/websites/edit/{$website['Website']['id']}")?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
<fieldset>
<legend>Website and FTP Details</legend>
<?php echo $form->input('Website.uri', array('label'=>array('text'=>'Web Address [URI]','title'=>'No http, eg: www.example.com'),'size'=>'40','maxlength'=>'150','error'=>'Please enter the Address.'))?> 
<?php echo $form->input('Website.aliases', array('label'=>array('text'=>'Aliases','title'=>'A list of alternate web addresses'),'row'=>3,'cols'=>50,'error'=>'Please enter the Address.'))?> 
<?php echo $form->input('Website.ftp_host', array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Host address.'))?> 
<?php echo $form->input('Website.ftp_username', array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Username.'))?> 
<?php echo $form->input('Website.ftp_password', array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Password.'))?> 
</fieldset>
<?php if(!empty($website['Website']['customer_id'])):?>
<?php echo $form->hidden('Website.customer_id',array('value'=>$website['Website']['customer_id']))?> 
<?php else:?>
<fieldset>
<legend>Customer</legend>
<?php echo $form->input('Website.customer_id',array('options'=>$customer,'div'=>false,'label'=>'Customer','error'=>'Please choose a Customer'));?> 
</fieldset>
<?php endif;?>
<?php echo $form->hidden('Website.id') ?>
<?php echo $form->submit('Save Changes')?> 
</form>