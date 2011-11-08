<?php if ($wizard): ?>
<div class="wizard hwr l">
<div>
<h1>Generate a report for a specific month</h1>
<?php echo $this->Form->create(null) ?> 
<?php echo $this->Form->input('date',array(
    'type'=>'date',
    'dateFormat' => 'MY',
    'minYear' => 2000,
    'maxYear' => date('Y')
));?> 
<?php echo $this->Form->input('types',array('label'=>'Type')) ?> 
<?php echo $this->Form->end('Apply') ?> 
</div>
<div>
<h1>Generate a report between 2 dates</h1>
<?php echo $this->Form->create(null) ?> 
<?php echo $this->Form->input('start_date',array(
    'type'=>'date',
    'dateFormat' => 'DMY',
    'minYear' => 2000,
    'maxYear' => date('Y')
)) ?> 
<?php echo $this->Form->input('end_date',array(
    'type'=>'date',
    'dateFormat' => 'DMY',
    'minYear' => 2000,
    'maxYear' => date('Y')
)) ?> 
<?php echo $this->Form->input('types',array('label'=>'Type')) ?> 
<?php echo $this->Form->end('Apply') ?> 
</div>
<div>
<h1>Generate list of all reports</h1>
<?php echo $this->Form->create(null) ?> 
<?php echo $this->Form->input('type',array(
    'options' => array('overdue'=>'Overdue','notoverdue'=>'Open (but not overdue)')
)) ?> 
<?php echo $this->Form->hidden('types',array('value'=>'all')) ?> 
<?php echo $this->Form->end('Apply') ?> 
</div>
</div>
<?php endif ?>
<section class="invoice detail list paginated">
<h1><?php echo $title_for_layout ?></h1>
<?php if (!empty($invoices)): ?>
<?php foreach($invoices as $invoice): ?>
<article>
<h1><?php echo $this->Html->link($invoice['Invoice']['reference'], array(
    'controller' => 'invoices', 'action' => 'view', $invoice['Invoice']['id']
)) ?></h1>
<p><span class="flag <?php echo Inflector::underscore($invoice['Invoice']['text_status']) ?>"><?php echo $invoice['Invoice']['text_status'] ?></span> <?php echo money_format('%.2n', $invoice['Invoice']['amount']) ?>. Issued <?php echo $this->Time->format('d-m-Y', $invoice['Invoice']['created']) ?> &amp;
<?php if ($invoice['Invoice']['date_invoice_paid'] !== null): ?>
Paid <?php echo $this->Time->format('d-m-Y', $invoice['Invoice']['date_invoice_paid']) ?>
<?php else: ?>
Due <?php echo $this->Time->format('d-m-Y', $invoice['Invoice']['due_date']) ?>
<?php endif ?>
</p>
</article>
<?php endforeach ?>
<ul class="pagelinks">
<?php $this->Paginator->options(array('url' => $this->request->query, 'convertKeys' => array('customer_id'))) ?>
<?php echo $this->Paginator->prev(__('Back'), array('class' => 'prev', 'tag' => 'li')) ?>
<li><?php echo $this->Paginator->counter('{:page} of {:pages}') ?></li>
<?php echo $this->Paginator->next(__('Next'), array('class' => 'next', 'tag' => 'li')) ?>
</ul>
<?php else: ?>
<div><p>No Invoices</p></div>
<?php endif ?>
</section>
