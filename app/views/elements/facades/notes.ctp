<h2>Notes</h2>
<ul class="tab_hooks">
<li>Notes [<b><?php echo $your_notes['count'] ?></b>]</li>
<li>Flagged [<b><?php echo $your_flagged_notes['count'] ?></b>]</li>
<li>All Flagged [<b><?php echo $flagged_notes['count'] ?></b>]</li>
<li>All Notes</li>
</ul>

<div class="tab_page your_notes note_list">
<?php if($your_notes['pages']>1):?>
<ul class="hook_ajax_pagination">
<?php if($your_notes['curr_page']>1):?>
<li><?php echo $html->link('Prev',"/notes/your_notes/".($your_notes['curr_page']-1)) ?></li>
<?php endif;?>
<?php if($your_notes['curr_page']<$your_notes['pages']):?>
<li><?php echo $html->link('Next',"/notes/your_notes/".($your_notes['curr_page']+1)) ?></li>
<?php endif;?>
</ul>
<?php endif;?>
<?php if(!empty($your_notes['items'])):?>
<ul class="note_list">
<?php foreach($your_notes['items'] as $n=>$note):?>
<li>
<h3><?php echo $textAssistant->sanitiseText("{$note['User']['name']}")?> (<?php echo $time->relativeTime($note['Note']['created']) ?>)</h3>
<h4><?php echo $textAssistant->link("{$note['Customer']['company_name']} - {$note['Service']['title']}","/customers/view/{$note['Customer']['id']}")?></h4>
<?php echo $textAssistant->htmlFormatted($note['Note']['description'])?>
</li>
<?php endforeach;?>
</ul>
<?php endif;?>
</div>

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
<li class="flagged">
<h3><?php echo $textAssistant->sanitiseText("{$note['User']['name']}")?> (<?php echo $time->relativeTime($note['Note']['created']) ?>)</h3>
<h4><?php echo $textAssistant->link("{$note['Customer']['company_name']} - {$note['Service']['title']}","/customers/view/{$note['Customer']['id']}")?></h4>
<?php echo $textAssistant->htmlFormatted($note['Note']['description'])?>
</li>
<?php endforeach;?>
</ul>
<?php endif;?>
</div>

<div class="tab_page flagged_notes note_list">
<?php if($flagged_notes['pages']>1):?>
<ul class="hook_ajax_pagination">
<?php if($flagged_notes['curr_page']>1):?>
<li><?php echo $html->link('Prev',"/notes/flagged_notes/".($flagged_notes['curr_page']-1)) ?></li>
<?php endif;?>
<?php if($flagged_notes['curr_page']<$flagged_notes['pages']):?>
<li><?php echo $html->link('Next',"/notes/flagged_notes/".($flagged_notes['curr_page']+1)) ?></li>
<?php endif;?>
</ul>
<?php endif;?>
<?php if(!empty($flagged_notes['items'])):?>
<ul class="note_list">
<?php foreach($flagged_notes['items'] as $n=>$note):?>
<li class="flagged">
<h3><?php echo $textAssistant->sanitiseText("{$note['User']['name']}")?> (<?php echo $time->relativeTime($note['Note']['created']) ?>)</h3>
<h4><?php echo $textAssistant->link("{$note['Customer']['company_name']} - {$note['Service']['title']}","/customers/view/{$note['Customer']['id']}")?></h4>
<?php echo $textAssistant->htmlFormatted($note['Note']['description'])?>
</li>
<?php endforeach;?>
</ul>
<?php endif;?>
</div>

<div class="tab_page all_notes note_list">
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
<h4><?php echo $textAssistant->link("{$note['Customer']['company_name']} - {$note['Service']['title']}","/customers/view/{$note['Customer']['id']}")?></h4>
<?php echo $textAssistant->htmlFormatted($note['Note']['description'])?>
</li>
<?php endforeach;?>
</ul>
<?php endif;?>
</div>