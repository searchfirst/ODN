<section class="note mini list">
<h1>Notes</h1>
<div class="filter_hooks">
<ul>
<li class="current"><?php echo $this->Html->link(__('You'), array(
    'controller' => 'notes', 'action' => 'index', '?' => array('user_id' => User::getCurrent('id'))
)) ?></li>
<li><?php echo $this->Html->link(__('Everyone'), array(
    'controller' => 'notes', 'action' => 'index'
)) ?></li>
</ul>
<ul>
<li class="current"><?php echo $this->Html->link(__('All'), array(
    'controller' => 'notes', 'action' => 'index', '?' => array('user_id' => User::getCurrent('id'))
)) ?></li>
<li><?php echo $this->Html->link(__('Flagged'), array(
    'controller' => 'notes', 'action' => 'index', '?' => array('user_id' => User::getCurrent('id'), 'flagged' => 1)
)) ?></li>
</ul>
</div>
<?php if (!empty($notes)): ?>
<?php foreach ($notes as $note): ?>
<article>
<h1><?php echo sprintf('%s %s', $note['User']['name'], $this->Time->format('d/m/Y', $note['Note']['created'])) ?>
<?php if (!empty($note['Customer'])): ?>
 in
<?php echo $this->Html->link($note['Customer']['company_name'], array(
    'controller' => 'customers', 'action' => 'view', $note['Customer']['id']
)) ?>
<?php if (!empty($note['Service'])): ?>
<?php echo sprintf('/%s', $note['Service']['title']) ?>
<?php endif ?>
<?php endif ?></h1>
<?php echo $this->Text->format(array(
    'text' => $note['Note']['description']
)) ?>
</article>
<?php endforeach ?>
<?php else: ?>
<?php endif ?>
</section>
