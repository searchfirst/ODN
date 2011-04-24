<nav id="menu">
<ul>
<li><?php echo $html->link('Dashboard',array('controller'=>'facades','action'=>'index'))?></li>
<li><?php echo $this->Html->link('Customers',array('controller'=>'customers','action'=>'index')) ?></li>
<?php /* Old Stuff that I may reuse
<div>		
<p><?php echo $html->link('New Customer',"/customers/add",array('class'=>'new_customer modalAJAX','title'=>'New Customer'))?></p>
<ul class="cloud">
<?php for($num=0;$num<10;$num++):?>
<li><?php echo $html->link(strtolower($num),'/customers/'.$num)?></li>
<?php endfor;?>
<?php for($chr=65,$letter=chr($chr); $chr<91; $chr++,$letter=chr($chr)):?>
<li><?php echo $html->link($letter,'/customers/'.strtolower($letter))?></li>
<?php endfor;?>
<li><?php echo $html->link('Resellers','/customers/resellers') ?></li>
</ul>
</div>		
 */ ?>
 <li><?php echo $this->Html->link('Invoices',array('controller'=>'invoices')) ?></li>
<?php if(isset($current_user) && !empty($external_links)):?>
<li class="tools">External Tools
<div>
<ul>
<?php foreach($external_links as $title=>$url):?>
<li><?php echo $html->link($title,$url);?></li>
<?php endforeach;?>
</ul>
</div>
</li>
<?php endif;?>
</ul>
</nav>
