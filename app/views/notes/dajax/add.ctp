<h2>Add Note</h2>
<form action="<?php echo $this->webroot?>notes/add" method="post" accept-charset="utf-8">
<fieldset>
<?php echo $form->input('Note.description',array('cols'=>50,'rows'=>19,'label'=>'Content'))?>
<?php if(isset($services)):?>
<?php echo $form->input('Note.service_id',array('options'=>$services,'label'=>'Service','empty'=>true)) ?>
<?php endif;?>
<?php echo $form->hidden('Note.customer_id')?>
<?php echo $form->hidden('Note.model',array('value'=>'Customer')) ?>
</fieldset>
<?php echo $form->submit('Add Note') ?>
</form>