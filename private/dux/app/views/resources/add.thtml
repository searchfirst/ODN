<h2>New Resource</h2>
<form action="<?php echo $html->url('/resources/add'); ?>" method="post">
<p> 
	<?php echo $form->labelTag('Resource/title', 'Title');?>
 	<?php echo $html->input('Resource/title', array('size' => '60'));?>
	<?php echo $html->tagErrorMsg('Resource/title', 'Please enter the Title.');?>
</p>
<p> 
	<?php echo $form->labelTag( 'Resource/description', 'Description');?>
 	<?php echo $html->textarea('Resource/description', array('cols' => '60', 'rows' => '10'));?>
	<?php echo $html->tagErrorMsg('Resource/description', 'Please enter the Description.');?>
</p>
<p>
	<?php echo $form->labelTag('Resource/file_upload', 'Choose a file'); ?>
	<?php echo $html->file('Resource/file_upload'); ?>
</p>
<p>
	<?php echo $html->submit('Add');?>
</p>
</form>