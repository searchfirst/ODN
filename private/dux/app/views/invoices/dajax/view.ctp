<?php setlocale(LC_MONETARY, 'en_GB.UTF-8'); ?>
<div class="options">
<?php // echo $this->renderElement('website/edit',array('id'=>$website['Website']['id'],'title'=>$website['Website']['uri']))?> 
</div>
<h2>Invoice no. <?php echo $invoice['Invoice']['reference']?></h2>

<dl>
<dt>Description</dt>
<dd><?php echo $invoice['Invoice']['description'] ?></dd>
<dt>Amount</dt>
<dd><?php echo money_format('%n',$invoice['Invoice']['amount']);
if((boolean)$invoice['Invoice']['vat_included']) echo " (Inc. VAT)";
else echo " (VAT Not Included)";
 ?></dd>
<dt>Issue Date</dt>
<dd><?php echo $time->niceShort($invoice['Invoice']['created']) ?></dd>
<dt>Due Date</dt>
<dd><?php echo $time->relativeTime($invoice['Invoice']['due_date']) ?> (<?php echo $time->nice($invoice['Invoice']['due_date']) ?>)</dd>
</dl>

<script type="text/javascript" charset="utf-8">
$('#ModifyStatusButton').bind('click',function(e){
	$(this).parent().find('a.thickbox').click();
	$('.date_pick').datePicker();
	return false;
});

</script>