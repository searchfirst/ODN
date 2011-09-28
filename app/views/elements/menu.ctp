<nav id="menu">
<ul>
<li><?php echo $html->link('Dashboard',array('controller'=>'facades','action'=>'index'))?></li>
<li><?php echo $this->Html->link('Customers',array('controller'=>'customers','action'=>'index')) ?></li>
<li><?php echo $this->Html->link('Invoices',array('controller'=>'invoices')) ?></li>
<li><?php echo $this->Html->link('Tools',array('controller'=>'utilities')) ?></li>
</ul>
</nav>
