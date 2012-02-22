<h1><?php echo !empty($title_for_layout) ? $title_for_layout : 'Search' ?></h1>
<?php echo $this->Form->create(false,array("type"=>"get")) ?> 
<?php echo $this->Form->input('q',array('label'=>'Search')) ?> 
<?php echo $this->Form->end('Search') ?> 
<?php if (!empty($results)): ?>
<div class="search detail list">
<?php foreach ($results as $result): ?>
<article>
<h1><?php echo $this->Searchable->viewLink($result) ?></h1>
<?php echo $this->Html->link('Go to Customer page',array('controller'=>'customers','action'=>'view',$result[$result['SearchIndex']['model']]['customer_id'])) ?>
<?php
echo $this->Text->htmlFormatted($result['SearchIndex']['data']);
?>
</article>
<?php endforeach ?>
</div>
<?php endif ?>
