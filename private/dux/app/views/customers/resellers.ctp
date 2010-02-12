<h2>Resellers</h2>
<ul class="item_list">
<?php foreach($resellers as $i=>$reseller):?>
<li class="item<?php echo $i%2?" even":""; ?>"><a href="/customers/view/<?php echo $reseller['Customer']['id'];?>"><?php echo $reseller['Customer']['company_name'];?></a></li>
<?php endforeach;?>
</ul>