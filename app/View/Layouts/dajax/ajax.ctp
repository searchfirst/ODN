<?php echo $content_for_layout;?>
<script>
$("#ServiceTitle").each(function() {
	if($(this).val()!='other') {
		$(this).parent().next("input").removeAttr('name').hide();
	};
});
$("#ServiceTitle").change(function() {
	var desiredName = $(this).attr('name');
	if($(this).val()=='other') {
		$(this).parent().next("input").attr('name',desiredName).fadeIn('fast');
		$(this).parent().next("input").select();
	} else {
		$(this).parent().next("input").removeAttr('name').fadeOut('fast');
	}
});
</script>