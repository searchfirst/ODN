<section class="service detail list">
<h1>Services</h1>
<ul class="hook_menu">
<li><?php echo $this->Html->link('Add Service',"/services/add?customer_id={$customer['Customer']['id']}",array('class'=>'add modalAJAX'));?></li>
<li><?php echo $this->Html->link('Add Website',"/websites/add?customer_id={$customer['Customer']['id']}",array('class'=>'add modalAJAX'));?></li>
</ul>
<?php foreach ($customer['Website'] as $website): ?>
<section>
<h1><?php echo $this->Html->link($website['uri'],"/websites/view/{$website['id']}",array('class'=>'modalAJAX'));?></h1>
<ul class="hook_menu">
<li><?php echo $this->Html->link('Edit Website',"/websites/edit/{$website['id']}",array('class'=>'modalAJAX')) ?></li>
</ul>
<?php foreach ($customer['Service'] as $service): ?>
<?php if ($service['website_id'] == $website['id']): ?>
<article class="<?php echo sprintf('%s highlight_service_%s',$this->Service->status($service['status'],'underscore'),$service['id']) ?>">
<h1><?php echo $this->Html->link($service['title'], "/services/view/{$service['id']}",array('class'=>'modalAJAX'))?></h1>
<ul class="hook_menu">
<li><?php echo $this->Html->link('Edit Service',"/services/edit/{$service['id']}",array('class'=>'modalAJAX')) ?></li>
<li><?php echo $this->Html->link('Change Status',"/services/change_status/{$service['id']}",array('class'=>'modalAJAX')) ?></li>
</ul>
<p><?php echo $this->Service->flagTag($service['status']) ?> service,
assigned to <?php echo $this->Html->link($service['User']['name'],"/users/view/{$service['User']['id']}") ?>.
<?php
if($service['cancelled']) {
	echo "Cancelled ".str_replace(', 00:00','',$this->Time->niceShort($service['cancelled'])).".";
} else {
	echo "Joined ".str_replace(', 00:00','',$this->Time->niceShort($service['joined'])).".";
}
?>
</p>
</article>
<?php endif ?>
<?php endforeach ?>
</section>
<?php endforeach ?>
<?php if(!empty($customer['InactiveLocation'])): ?>
<?php foreach($customer['InactiveLocation'] as $r_website): ?>
<section>
<h1><?php echo $this->Html->link($r_website['uri'],"/websites/view/{$r_website['id']}",array('class'=>'modalAJAX'));?></h1>
<ul class="hook_menu">
<li><?php echo $this->Html->link('Edit Website',"/websites/edit/{$r_website['id']}",array('class'=>'modalAJAX')) ?></li>
</ul>
<div><p>No Services</p></div>
</section>
<?php endforeach ?>
<?php endif ?>
</section>
