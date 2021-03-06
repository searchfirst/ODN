<?php if($all_notes['pages']>1):?>
<ul class="hook_ajax_pagination">
<?php if($all_notes['curr_page']>1):?>
<li><?php echo $html->link('Prev',"/notes/all_notes/".($all_notes['curr_page']-1)) ?></li>
<?php endif;?>
<?php if($all_notes['curr_page']<$all_notes['pages']):?>
<li><?php echo $html->link('Next',"/notes/all_notes/".($all_notes['curr_page']+1)) ?></li>
<?php endif;?>
</ul>
<?php endif;?>
<?php if(!empty($all_notes['items'])):?>
<ul class="note_list">
<?php foreach($all_notes['items'] as $n=>$note):?>
<li>
<h3><?php echo $textAssistant->sanitiseText("{$note['User']['name']}")?> (<?php echo $time->relativeTime($note['Note']['created']) ?>)</h3>
<p><b><?php echo $textAssistant->link("{$note['Customer']['company_name']} - {$note['Service']['title']}","/customers/view/{$note['Customer']['id']}")?></b></p>
<?php echo $textAssistant->htmlFormatted($note['Note']['description'])?>
</li>
<?php endforeach;?>
</ul>
<?php endif;?>