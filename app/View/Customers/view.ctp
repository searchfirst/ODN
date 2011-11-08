<h1><?php echo $this->Customer->flag($customer['Customer']) ?> <?php echo $customer['Customer']['company_name'] ?>
<?php if (!empty($customer['Reseller']['id'])): ?>
<span class="referrer">Reseller: <?php echo $this->Html->link($customer['Reseller']['company_name'],array('action'=>'view',$customer['Reseller']['id'])) ?></span>
<?php endif ?>
</h1>
<div><?php echo $this->Html->link('Edit Customer', array('action' => 'edit', $customer['Customer']['id']), array('role' => 'button')) ?></div>
<div class="detail list">
<h1>Information</h1>
<ul>
<li><?php echo $this->Html->link(__('Contacts'), array(
    'controller' => 'contacts', 'action' => 'index', '?' => array('customer_id' => $customer['Customer']['id'])
)) ?></li>
<li><?php echo $this->Html->link(__('Websites'), array(
    'controller' => 'websites', 'action' => 'index', '?' => array('customer_id' => $customer['Customer']['id'])
)) ?></li>
<li><?php echo $this->Html->link(__('Services'), array(
    'controller' => 'services', 'action' => 'index', '?' => array('customer_id' => $customer['Customer']['id'])
)) ?></li>
<li><?php echo $this->Html->link(__('Invoices'), array(
    'controller' => 'invoices', 'action' => 'index', '?' => array('customer_id' => $customer['Customer']['id'])
)) ?></li>
<li><?php echo $this->Html->link(__('Customers'), array(
    'controller' => 'customers', 'action' => 'index', '?' => array('customer_id' => $customer['Customer']['id'])
)) ?></li>
<li><?php echo $this->Html->link(__('Notes'), array(
    'controller' => 'notes', 'action' => 'index', '?' => array('customer_id' => $customer['Customer']['id'])
)) ?></li>
</ul>
</div>
