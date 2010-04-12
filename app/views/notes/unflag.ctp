<form action="/notes/unflag/<?php echo $this->data['Note']['id'];?>" method="get" accept-charset="utf-8">
<?php echo $form->hidden('Note.id');?> 
<?php echo $form->hidden('Note.flagged');?> 
<p>Do you want to unflag this note? <?php echo $form->submit('Yes, unflag it',array('div'=>false));?></p>
</form>