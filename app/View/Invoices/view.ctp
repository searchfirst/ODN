<h1><?php echo __('Invoice') ?></h1>
<dl>
<dt>Description</dt>
<dd><?php echo $invoice['Invoice']['description'] ?></dd>
<dt>Amount</dt>
<dd><?php echo money_format('%n',$invoice['Invoice']['amount']);
if((boolean)$invoice['Invoice']['vat_included']) echo " (Inc. VAT)";
else echo " (VAT Not Included)";
 ?></dd>
<dt>Issue Date</dt>
<dd><?php echo $this->Time->niceShort($invoice['Invoice']['created']) ?></dd>
<dt>Due Date</dt>
<dd><?php echo $this->Time->relativeTime($invoice['Invoice']['due_date']) ?> (<?php echo $this->Time->nice($invoice['Invoice']['due_date']) ?>)</dd>
</dl>
