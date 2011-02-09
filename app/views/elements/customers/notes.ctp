<section class="note detail list">
<h1>Notes</h1>
<?php foreach($customer['Note'] as $note): ?>
<article class="<?php
if($note['flagged']) echo "flagged ";
$model = strtolower($note['model']);
$model_id = $note[$model.'_id'];
echo "highlight_{$model}_{$model_id}";
?>">
<h1><?php echo $note['User']['name']?> (<?php echo $this->Time->niceShort($note['created'])?>)</h1>
<?php if(!empty($note['Service']['user_id']) && ($currentUser['User']['id'] == $note['User']['id'] || $currentUser['User']['id'] == $note['Service']['user_id'])): ?>
<ul class="hook_menu">
<?php if($note['flagged']): ?>
<li><?php echo $this->Html->link("Unflag","/notes/unflag/{$note['id']}",array('class'=>'modalAJAX')) ?></li>
<?php else: ?>
<li><?php echo $this->Html->link("Flag","/notes/flag/{$note['id']}",array('class'=>'modalAJAX')) ?></li>
<?php endif ?>
</ul>
<?php endif ?>
<?php echo $this->T->format(array(
	'text' => $this->Note->flagTag($note)."\n".$note['description']
)) ?> 
</article>
<?php endforeach ?>
</section>
