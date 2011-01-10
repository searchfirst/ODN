<h2>Activity Monitor</h2>
<table>
<thead>
<tr>
<th></th>
<?php foreach($dates as $date):?>
<th><?php echo $date; ?></th>
<?php endforeach;?>
</tr>
</thead>
<tbody>
<?php foreach($customers as $customer):?>
<tr>
<th><?php echo $customer[0]['company_service_hash'] ?></th>
<?php foreach($dates as $date):?>
<td>
<?php if(!empty($customer_date_table[$customer[0]['company_service_hash']][$date])):?>
<?php foreach($customer_date_table[$customer[0]['company_service_hash']][$date] as $note): ?>
<?php echo $html->link('Note',sprintf('/admin/customers/view/%s',$note['Customer']['id']),array('class'=>'td_table_link')); ?>
<?php endforeach; ?>
<?php endif;?>
</td>
<?php endforeach;?>
</tr>
<?php endforeach;?>
</tbody>
</table>