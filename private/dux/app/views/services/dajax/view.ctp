<div class="options">
<?php echo $this->renderElement('edit_form',array('id'=>$service['Service']['id'],'title'=>$service['Service']['title']))?> 
<?php echo $this->renderElement('service/modify_status',array('id'=>$service['Service']['id'],'selected'=>$service['Service']['status'],'title'=>$service['Service']['title']))?> 
</div>
<h2><?php echo $service['Service']['title']?></h2>

<h3>Details</h3>
<?php echo $textAssistant->htmlFormatted($service['Service']['description'])?>

<div class="note_list">
<h3>Notes</h3>

<div>
<table>
<colgroup span="4">
<col width="*" span="1" />
<col width="90px" span="1" />
<col width="120px" span="2" />
</colgroup>
<thead>
<tr>
<th>Note</th>
<th>Role</th>
<th>Author</th>
<th>Date</th>
</tr>
</thead>
<tfoot>
<tr><th colspan="4">
<div class="options"><form method="post" action="/notes/add" class="model_command">
<button name="data[Note][submit]" value="" class="thickbox" alt="/ajax/notes/add?customer_id=<?php echo $service['Customer']['id']?>&amp;height=400&amp;width=600" title="Add Note">
<img src="/img/new-item-icon.png" alt="" />
Add Note</button>
<input type="hidden" name="data[Referrer][customer_id]" value="<?php echo $service['Customer']['id'] ?>" />
</form>
</div></th></tr>
</tfoot>
<tbody>
<?php if(!empty($service['Note'])):?>
<?php foreach($service['Note'] as $note):?>
<tr>
<td><?php echo $note['description']?></td>
<td><?php echo $note['model']?></td>
<td><?php echo $note['User']['name']?></td>
<td><?php echo $time->niceShort($note['created'])?></td>
</tr>
<?php endforeach;?>
<?php else:?>
<tr><td colspan="4">No Notes</td></tr>
<?php endif;?>
</tbody>
</table>
</div>
</div>
<script type="text/javascript" charset="utf-8">
$('#ModifyStatusButton').bind('click',function(e){
	$(this).parent().find('a.thickbox').click();
	$('.date_pick').datePicker();
	return false;
});

</script>