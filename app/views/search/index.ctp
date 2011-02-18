<h1><?php echo !empty($title_for_layout) ? $title_for_layout : 'Search' ?></h1>
<?php echo $form->create(false,array("type"=>"get")) ?> 
<?php echo $form->input('q',array('label'=>'Search')) ?> 
<?php echo $form->end('Search') ?> 
<?php if (!empty($results)): ?>
<div class="search detail list">
<?php foreach ($results as $result): ?>
<article>
<h1><?php echo $this->Searchable->viewLink($result) ?></h1>
<?php echo $html->link('Go to Customer page',array('controller'=>'customers','action'=>'view',$result[$result['SearchIndex']['model']]['customer_id'])) ?>
<?php
echo $this->TextAssistant->htmlFormatted($result['SearchIndex']['data']);
?>
</article>
<?php endforeach ?>
</div>
<?php endif ?>
