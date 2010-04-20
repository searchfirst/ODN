<h2>Invoice Summary</h2>
<ul class="tab_hooks">
<li><a href="#invoices_open">Open [<?php echo $open_invoices_count;?>]</a></li>
<li><a href="#invoices_overdue">Overdue [<?php echo $overdue_invoices_count;?>]</a></li>
<li><a href="#invoices_recent">Recently Paid [<?php echo $recently_paid_invoices_count;?>]</a></li>
</ul>

<?php if(!empty($invoices)):?>
<?php foreach($invoices as $invoice_type=>$t_invoice):?>
<div class="tab_page project_list invoice list" id="invoices_<?php echo Inflector::underscore($invoice_type)?>">
<h3><?php echo Inflector::humanize($invoice_type) ?></h3>
<?php if(!empty($t_invoice)):?>
<table>
<thead><tr>
<th>Reference</th><th>Customer</th><th>Service</th><th>Amount (inc. VAT)</th><th>Date Raised</th><th>Date Due</th>
</tr></thead>
<tbody>
<?php foreach($t_invoice as $x=>$invoice):?>
<tr>
<th><?php echo $html->link($invoice['Invoice']['reference'],"/invoices/view/{$invoice['Invoice']['id']}") ?></th>
<td><?php echo $html->link($invoice['Customer']['company_name'],"/customers/view/{$invoice['Customer']['id']}");?></td>
<?php if(!empty($invoice['Invoice']['service_id'])):?>
<td><?php echo $html->link($invoice['Service']['title'],"/services/view/{$invoice['Service']['id']}");?></td>
<?php else:?>
<td></td>
<?php endif;?>
<td><?php echo Invoice::getGrandTotal($invoice);?></td>
<td><?php echo substr($time->niceShort($invoice['Invoice']['created']),0,-7) ?></td>
<td><?php echo substr($time->niceShort($invoice['Invoice']['due_date']),0,-7) ?></td>
</tr>
<?php endforeach;?>
</tbody>
</table>
<?php else:?>
<p>No Invoices</p>
<?php endif;?>
</div>
<?php endforeach;?>
<?php endif;?>