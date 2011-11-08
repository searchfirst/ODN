<section class="invoice detail list paginated">
<h1><?php echo __('Invoices') ?></h1>
<?php if (!empty($invoices)): ?>
<?php foreach($invoices as $invoice): ?>
<article>
<h1><?php echo $this->Html->link($invoice['reference'], array(
    'controller' => 'invoices', 'action' => 'view', $invoice['id']
)) ?></h1>
<p><span class="flag <?php echo Inflector::underscore($invoice['text_status']) ?>"><?php echo $invoice['text_status'] ?></span> <?php echo money_format('%.2n', $invoice['amount']) ?>. Issued <?php echo $this->Time->format('d-m-Y', $invoice['created']) ?> &amp;
<?php if ($invoice['date_invoice_paid'] !== null): ?>
Paid <?php echo $this->Time->format('d-m-Y', $invoice['date_invoice_paid']) ?>
<?php else: ?>
Due <?php echo $this->Time->format('d-m-Y', $invoice['due_date']) ?>
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
