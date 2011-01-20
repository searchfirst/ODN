<h2>Cancel Invoice</h2>
<p><?php __('Do you want to Cancel this invoice and keep a record?') ?></p>
<?php echo $form->create('Invoice',array('action'=>'edit')) ?> 
<?php echo $form->input('Note.description',array(
	'rows'=>3,
	'label'=>__('Note',true),
	'value'=>sprintf('%s: %s.',__('Cancelled',true),$time->nice($time->gmt()))
)) ?> 
<?php echo $form->hidden('Invoice.cancelled',array('value'=>true)) ?> 
<?php echo $form->hidden('Invoice.id') ?> 
<?php echo $form->end(__('Cancel Invoice',true)) ?>
