<form method="post" action="<?php echo $this->webroot?>services/change_status/<?php echo $service['Service']['id']?>">
<label for="ServiceStatus">Status</label>
<select id="ServiceStatus" name="data[Service][status]">
<option value="2"<?php if($service['Service']['status']==2) echo " selected=\"selected\" disabled=\"disabled\""?>>Active</option>
<option value="0"<?php if($service['Service']['status']==0) echo " selected=\"selected\" disabled=\"disabled\""?>>Cancelled</option>
<option value="1"<?php if($service['Service']['status']==1) echo " selected=\"selected\" disabled=\"disabled\""?>>Pending</option>
<option value="3"<?php if($service['Service']['status']==3) echo " selected=\"selected\" disabled=\"disabled\""?>>Complete</option>
</select>
<input type="hidden" name="data[Service][cancelled]" value="<?php echo strftime("%Y-%m-%d") ?> 00:00:00" id="DatePickHidden" class="date_pick_input" />
<div id="date_pick_block">Change Date</div>
<label for="NotesDescription">Note</label>
<textarea name="data[Note][description]" rows="5" cols="20" id="NotesDescription"></textarea>
<input type="hidden" name="data[Note][model]" value="Service" />
<input type="hidden" name="data[Note][customer_id]" value="<?php echo $service['Service']['customer_id'] ?>">
<input type="hidden" name="data[Note][service_id]" value="<?php echo $service['Service']['id'] ?>">
<input type="submit" value="Save" />
</form>
<script type="text/javascript" charset="utf-8">
$('#date_pick_block').bind('click',function(){
	$(this).css('background-image','none').css('padding-left','0px')
	$('#date_pick_block').datePicker({
		inline:true,
		startDate:'2000-01-01'
	}).bind('dateSelected',function(e, selectedDate, $td){
		$('#DatePickHidden').attr('value',selectedDate.asString()+' 00:00:00');
	});
});
</script>