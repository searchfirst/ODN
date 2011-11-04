<section class="project mini list">
<h1>Customers</h1>
<div class="filter_hooks">
<ul>
<li class="current"><?php echo $this->Html->link('Active', array(
    'controller' => 'customers', 'action' => 'by_service', '?' => array('status' => Service::$status['Active'])
)) ?></li>
<li><?php echo $this->Html->link('Cancelled', array(
    'controller' => 'customers', 'action' => 'by_service', '?' => array('status' => Service::$status['Cancelled'])
)) ?></li>
<li><?php echo $this->Html->link('Complete', array(
    'controller' => 'customers', 'action' => 'by_service', '?' => array('status' => Service::$status['Complete'])
)) ?></li>
<li><?php echo $this->Html->link('Pending', array(
    'controller' => 'customers', 'action' => 'by_service', '?' => array('status' => Service::$status['Pending'])
)) ?></li>
</ul>
</div>
<?php if (!empty($customers)): ?>
<?php foreach ($customers as $customer): ?>
<article>
<h1><?php echo $this->Html->link($customer['Customer']['company_name'], array(
    'controller'=>'customers','action'=>'view',$customer['Customer']['id']
)) ?></h1>
<ul>
<?php foreach ($customer['Service'] as $service): ?>
<li><?php echo $service['title'] ?></li>
<?php endforeach ?>
</ul>
</article>
<?php endforeach ?>
<?php else: ?>
<article><h1>No Projects</h1></article>
<?php endif ?>
</section>
