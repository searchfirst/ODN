<div class="service_info infobox">
<div class="options">
<?php echo $html->link('Add Service',"/ajax/services/add?customer_id={$customer['Customer']['id']}",array('class'=>'add button modalAJAX'));?>
<?php echo $html->link('Add Website',"/ajax/websites/add?customer_id={$customer['Customer']['id']}",array('class'=>'add button modalAJAX'));?>
</div>
<h3>Services</h3>
<table class="item_list">
<thead>
<tr><th>Service</th><th>Technician</th><th>Schedule</th><th>Status</th><th>Signup/Cancel</th></tr>
</thead>
<tbody>
<?php $x_website = '';?>
<?php foreach($customer['Service'] as $service):?>
<?php if($x_website!=$service['Website']['uri']):?>
<tr><th colspan="5"><ul class="jd_menu"><li>
<?php echo $html->link($service['Website']['uri'],"/ajax/websites/view/{$service['Website']['id']}?width=400;height=200",array('class'=>'modalAJAX'));?><?php
if(!empty($service['Website']['aliases'])){
echo ' <small>('.$service['Website']['aliases'].')</small>';
}
?>
<ul>
<li><?php echo $html->link('Edit Website',"/ajax/websites/edit/{$service['Website']['id']}",array('class'=>'modalAJAX')) ?></li>
</ul>
</li></ul></th></tr>
<?php $x_website = $service['Website']['uri']; endif; ?>
<tr class="<?php
echo strtolower($status->serviceStatus($service['status']));
echo " highlight_service_".$service['id'];
?>">
<td><ul class="jd_menu"><li>
<?php echo $html->link($service['title'], "/ajax/services/view/{$service['id']}?width=600;height=400",array('class'=>'modalAJAX'))?>
<ul>
<li><?php echo $html->link('Edit Service',"/ajax/services/edit/{$service['id']}",array('class'=>'modalAJAX')) ?></li>
<li><?php echo $html->link('Change Status',"/ajax/services/change_status/{$service['id']}",array('class'=>'modalAJAX')) ?></li>
</ul>
</li></ul></td>
<td><?php echo $service['User']['name'] ?></td>
<td><?php echo $status->serviceSchedule($service['schedule']);?></td>
<td><?php echo $status->serviceStatus($service['status']) ?></td>
<td><?php echo str_replace(', 00:00','',$time->niceShort($service['joined']));
if($service['cancelled']) echo '/'.str_replace(', 00:00','',$time->niceShort($service['cancelled']));?></td>
</tr>
<?php endforeach;?>
<?php if(empty($customer['Service'])):?>
<tr><td colspan="5">No Services</td></tr>
<?php endif;?>
</tbody>
</table>
</div>