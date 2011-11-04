<h1>Customers</h1>
<ul class="item_list">
<?php foreach ($customers as $i => $customer): ?>
<li>
<?php
echo $this->Html->link($customer['Customer']['company_name'], array(
    'controller' => 'customers',
    'action' => 'view',
    $customer['Customer']['id']
))
?>
</li>
<?php endforeach ?>
</ul>
