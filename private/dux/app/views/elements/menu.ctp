<div id="d_menu">
<ul>
	<li><?php echo $html->link('Dashboard','/',array('class'=>'dashboard','title'=>'Dashboard'))?></li>
	<li class="customers"><span>Customers</span>
		<div>		
		<ul class="cloud">
		<?php for($num=0;$num<10;$num++):?>
			<li><?php echo $html->link(strtolower($num),'/customers/'.$num)?></li>
		<?php endfor;?>
		<?php for($chr=65,$letter=chr($chr); $chr<91; $chr++,$letter=chr($chr)):?>
			<li><?php echo $html->link($letter,'/customers/'.strtolower($letter))?></li>
		<?php endfor;?>
		</ul>
		<p><?php echo $html->link('Resellers','/customers/resellers') ?></p>
		</div>		
	</li>
	<li><?php echo $html->link('New Customer',array('controller'=>'customers','action'=>'add','alt_content'=>'dajax'),array('class'=>'new_customer thickbox','title'=>'New Customer'))?></li>
	<li><?php echo $html->link('Notes',array('controller'=>'notes','action'=>'index'),array('class'=>'notes','title'=>'Notes'))?></li>
	<li class="wizards"><span>Wizards</span>
		<div>
		<ul>
			<li><a href="#">Quarterly Summary</a></li>
			<li><a href="#">Invoice Summary</a></li>
		</ul>
		</div>
	</li>
	<?php if(isset($current_user)):?>
	<li class="tools"><span>Tools</span>
		<div>
		<ul>
			<li><a href="https://login.fasthosts.co.uk/">Fasthosts</a></li>
			<li><a href="http://whiteboard.searchfirst.co.uk">Whiteboard</a></li>
			<li><a href="http://hit-me.co.uk/cgi-bin/x.cgi?NAVG=SignUp">HitMe [New Tracker]</a></li>
			<li><a href="http://hit-me.co.uk/cgi-bin/admin/admin.cgi">HitMe [Admin]</a></li>
			<li><a href="http://hit-me.co.uk/cgi-bin/admin/admin.cgi?NAVG=AM">HitMe [Accounts Manager]</a></li>
		</ul>
		</div>
	</li>
	<?php endif;?>
</ul>
</div>