<div id="d_menu">
<ul>
<li><?php echo $html->link('Dashboard','/',array('class'=>'dashboard','title'=>'Dashboard'))?></li>
<li class="customers">Customers
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
</li>
<li class="wizards">Invoices
<div>
<ul>
<li><a href="/invoices">Invoice Summary</a></li>
<li><a href="/invoices/wizard">Invoice Report Wizard</a></li>
</ul>
</div>
</li>
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
</div>