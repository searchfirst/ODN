<div class="service_info infobox">
<h3>Services</h3>
<ul class="hook_menu">
<li><?php echo $html->link('Add Service',"/services/add?customer_id={$customer['Customer']['id']}",array('class'=>'add modalAJAX'));?></li>
<li><?php echo $html->link('Add Website',"/websites/add?customer_id={$customer['Customer']['id']}",array('class'=>'add modalAJAX'));?></li>
</ul>
<?php $x_website = '';?>
<?php foreach($customer['Service'] as $service):?>
<?php if($x_website!=$service['Website']['uri']):?>
<?php
foreach($customer['Website'] as $cw=>$c_websites) {
	if($service['Website']['uri'] == $c_websites['uri']) unset($customer['Website'][$cw]);
}
?>
<h4><?php echo $html->link($service['Website']['uri'],"/websites/view/{$service['Website']['id']}",array('class'=>'modalAJAX'));?></h4>
<?php /*if(!empty($service['Website']['aliases'])){
echo ' <small>('.$service['Website']['aliases'].')</small>';
}*/ ?>
<ul class="hook_menu">
<li><?php echo $html->link('Edit Website',"/websites/edit/{$service['Website']['id']}",array('class'=>'modalAJAX')) ?></li>
</ul>
<?php $x_website = $service['Website']['uri']; endif; ?>
<div class="<?php
echo strtolower($status->serviceStatus($service['status']));
echo " highlight_service_".$service['id'];
?>">
<h5><?php echo $html->link($service['title'], "/services/view/{$service['id']}",array('class'=>'modalAJAX'))?></h5>
<ul class="hook_menu">
<li><?php echo $html->link('Edit Service',"/services/edit/{$service['id']}",array('class'=>'modalAJAX')) ?></li>
<li><?php echo $html->link('Change Status',"/services/change_status/{$service['id']}",array('class'=>'modalAJAX')) ?></li>
</ul>
<p><?php echo $status->getStatusString('Service',$service['status']).' '.$status->getLcScheduleString('Service',$service['schedule']);?> service,
assigned to <?php echo $html->link($service['User']['name'],"/users/view/{$service['User']['id']}") ?>.
<?php
if($service['cancelled']) {
	echo "Cancelled ".str_replace(', 00:00','',$time->niceShort($service['cancelled'])).".";
} else {
	echo "Joined ".str_replace(', 00:00','',$time->niceShort($service['joined'])).".";
}
?>
</p>
</div>
<?php endforeach;?>
<?php if(!empty($customer['Website'])):?>
<?php foreach($customer['Website'] as $r_website):?>
<h4><?php echo $html->link($r_website['uri'],"/websites/view/{$r_website['id']}",array('class'=>'modalAJAX'));?></h4>
<ul class="hook_menu">
<li><?php echo $html->link('Edit Website',"/websites/edit/{$r_website['id']}",array('class'=>'modalAJAX')) ?></li>
</ul>
<div><p>No Services</p></div>
<?php endforeach;?>
<?php endif;?>
</div>