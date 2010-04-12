<div class="tab_page your_flagged_notes note_list">
<?php if($your_flagged_notes['pages']>1):?>
<ul class="hook_ajax_pagination">
<?php if($your_flagged_notes['curr_page']>1):?>
<li><?php echo $html->link('Prev',"/notes/your_flagged_notes/".($your_flagged_notes['curr_page']-1)) ?></li>
<?php endif;?>
<?php if($your_flagged_notes['curr_page']<$your_flagged_notes['pages']):?>
<li><?php echo $html->link('Next',"/notes/your_flagged_notes/".($your_flagged_notes['curr_page']+1)) ?></li>
<?php endif;?>
</ul>
<?php endif; ?>
<?php if(!empty($your_flagged_notes['items'])):?>
<ul class="note_list">
<?php foreach($your_flagged_notes['items'] as $n=>$note):?>
<li>
<h3><?php echo $textAssistant->sanitiseText("{$note['User']['name']}")?> (<?php echo $time->relativeTime($note['Note']['created']) ?>)</h3>
<p><b><?php echo $textAssistant->link("{$note['Customer']['company_name']} - {$note['Service']['title']}","/customers/view/{$note['Customer']['id']}")?></b></p>
<?php echo $textAssistant->htmlFormatted($note['Note']['description'])?>
</li>
<?php endforeach;?>
</ul>
<?php endif;?>
</div>