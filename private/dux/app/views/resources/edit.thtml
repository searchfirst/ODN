<h2>Edit Resource</h2>
<form action="<?php echo $html->url('/resources/edit/'.$html->tagValue('Resource/id')); ?>" method="post">
<div class="optional"> 
	<?php echo $form->labelTag('Resource/title', 'Title');?>
 	<?php echo $html->input('Resource/title', array('size' => '60'));?>
	<?php echo $html->tagErrorMsg('Resource/title', 'Please enter the Title.');?>
</div>
<div class="optional"> 
	<?php echo $form->labelTag('Resource/slug', 'Slug');?>
 	<?php echo $html->input('Resource/slug', array('size' => '60'));?>
	<?php echo $html->tagErrorMsg('Resource/slug', 'Please enter the Slug.');?>
</div>
<div class="optional"> 
	<?php echo $form->labelTag( 'Resource/description', 'Description' );?>
 	<?php echo $html->textarea('Resource/description', array('cols' => '60', 'rows' => '10'));?>
	<?php echo $html->tagErrorMsg('Resource/description', 'Please enter the Description.');?>
</div>
<div class="optional"> 
	<?php echo $form->labelTag('Resource/image_id', 'Image Id');?>
 	<?php echo $html->input('Resource/image_id', array('size' => '60'));?>
	<?php echo $html->tagErrorMsg('Resource/image_id', 'Please enter the Image Id.');?>
</div>
<?php echo $html->hidden('Resource/id')?>
<div class="submit">
	<?php echo $html->submit('Save');?>
</div>
</form>
<ul class="actions">
	<li><?php echo $html->link('List resources', '/resources/index')?></li>
</ul>
