<h2>Customers</h2>
<ul class="tab_hooks">
<li>Active <?php echo !empty($active_projects)?"[".count($active_projects)."]":'[0]'?></li>
<li>Cancelled <?php echo !empty($cancelled_projects)?"[".count($cancelled_projects)."]":'[0]'?></li>
<li>Other <?php echo !empty($other_projects)?"[".count($other_projects)."]":'[0]'?></li>
</ul>
<div class="tab_page active_projects_list project_list">
<?php if(!empty($active_projects)):?>
<h3>Active Projects [<?php echo count($active_projects);?>]</h3>
<ul>
<?php $current_customer=''; foreach($active_projects as $i=>$active_project):?>
<?php if($i==0):?>
<li>
<?php endif;?>
<?php if($i>0 && $current_customer!=$active_project['Customer']['id']):?>
</li>
<li>
<?php endif;?>
<?php if($current_customer!=$active_project['Customer']['id']):?>
<h4><?php echo $textAssistant->link($active_project['Customer']['company_name'],"/customers/view/{$active_project['Customer']['id']}");?></h4>
<?php $current_customer = $active_project['Customer']['id'];?>
<?php endif; ?>
<p><b><?php echo $textAssistant->link($active_project['Service']['title'],"/services/view/{$active_project['Service']['id']}",array('class'=>'modalAJAX')) ?> 
[<?php echo $active_project['Website']['uri'] ?>]:</b> Started <?php echo $time->relativeTime($active_project['Service']['joined']) ?></p>
<?php endforeach; $current_customer='';?>
</li>
</ul>
<?php endif ?>
</div>

<div class="tab_page cancelled_projects_list project_list">
<?php if(!empty($cancelled_projects)):?>
<h3>Cancelled Projects [<?php echo count($cancelled_projects);?>]</h3>
<ul>
<?php $current_customer=''; foreach($cancelled_projects as $i=>$cancelled_project):?>
<?php if($i==0):?>
<li>
<?php endif;?>
<?php if($i>0 && $current_customer!=$cancelled_project['Customer']['id']):?>
</li>
<li>
<?php endif;?>
<?php if($current_customer!=$cancelled_project['Customer']['id']):?>
<h4><?php echo $textAssistant->link($cancelled_project['Customer']['company_name'],"/customers/view/{$cancelled_project['Customer']['id']}");?></h4>
<?php $current_customer = $cancelled_project['Customer']['id'];?>
<?php endif; ?>
<p><b><?php echo $textAssistant->link($cancelled_project['Service']['title'],"/services/view/{$cancelled_project['Service']['id']}",array('class'=>'modalAJAX')) ?> 
[<?php echo $cancelled_project['Website']['uri'] ?>]:</b> Cancelled <?php echo $time->relativeTime($cancelled_project['Service']['cancelled']) ?></p>
<?php endforeach; $current_customer='';?>
</li>
</ul>
<?php endif ?>
</div>

<div class="tab_page other_projects_list project_list">
<?php if(!empty($other_projects)):?>
<h3>Other Projects [<?php echo count($other_projects);?>]</h3>
<ul>
<?php $current_customer=''; foreach($other_projects as $i=>$other_project):?>
<?php if($i==0):?>
<li>
<?php endif;?>
<?php if($i>0 && $current_customer!=$other_project['Customer']['id']):?>
</li>
<li>
<?php endif;?>
<?php if($current_customer!=$other_project['Customer']['id']):?>
<h4><?php echo $textAssistant->link($other_project['Customer']['company_name'],"/customers/view/{$other_project['Customer']['id']}");?></h4>
<?php $current_customer = $other_project['Customer']['id'];?>
<?php endif; ?>
<p><b><?php echo $textAssistant->link($other_project['Service']['title'],"/services/view/{$other_project['Service']['id']}",array('class'=>'modalAJAX'))?> 
[<?php echo $other_project['Website']['uri'] ?>]:</b> <?php echo ($other_project['Service']['status']==SERVICE_STATUS_COMPLETE)?"Completed":"Set as Pending" ?> <?php echo $time->relativeTime($other_project['Service']['modified']) ?></p>
<?php endforeach; $current_customer='';?>
</li>
</ul>
<?php endif; ?>
</div>