<h2>Notes</h2>
<div class="note_list">
<ul class="note_list">
<?php foreach($notes as $x=>$note):?>
<li class="<?php echo $x%2?"even":"odd" ?>">
<span><?php echo $html->link($note['Customer']['company_name'],"/customers/view/{$note['Customer']['id']}")?></span>
<b><?php echo $note['User']['name'] ?> (<?php echo $time->niceShort($note['Note']['created']);?>)</b>:
<?php echo $textAssistant->htmlFormatted($note['Note']['description']) ?></li>
<?php endforeach;?>
</ul>
</div>