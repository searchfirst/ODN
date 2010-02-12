<h2><?php echo $textAssistant->sanitiseText($this->pageTitle)?></h2>
<div class="secondary">
<h3>Recent Activity</h3>
<p>Coming as soon as I implement notes</p>
</div>
<div class="primary">
<?php if(!empty($active_projects)):?>
<div class="active_projects_list project_list">
<h3 class="collapse_hook hidemore">Active Projects [<?php echo count($active_projects);?>]</h3>
<dl class="collapse initial_show">
<?php $current_customer=''; foreach($active_projects as $i=>$active_project):?>
<?php if($current_customer!=$active_project['Customer']['id']):?>
<dt><?php echo $textAssistant->sanitiseText($active_project['Customer']['company_name']);?></dt>
<?php $current_customer = $active_project['Customer']['id'];?>
<?php endif; ?>
<dd><em><?php echo $html->link($active_project['Service']['title'],"/customers/view/{$active_project['Customer']['id']}") ?> 
[<?php echo $active_project['Website']['uri'] ?>]:</em> Commenced <?php echo $time->relativeTime($active_project['Service']['joined']) ?></dd>
<?php endforeach; $current_customer='';?>
</dl>
</div>
<?php endif ?>

<?php if(!empty($cancelled_projects)):?>
<div class="cancelled_projects_list project_list">
<h3 class="collapse_hook">Cancelled Projects [<?php echo count($cancelled_projects);?>]</h3>
<dl class="collapse">
<?php $current_customer=''; foreach($cancelled_projects as $i=>$cancelled_project):?>
<?php if($current_customer!=$cancelled_project['Customer']['id']):?>
<dt><?php echo $textAssistant->sanitiseText($cancelled_project['Customer']['company_name']);?></dt>
<?php $current_customer = $cancelled_project['Customer']['id'];?>
<?php endif; ?>
<dd><em><?php echo $html->link($cancelled_project['Service']['title'],"/customers/view/{$cancelled_project['Customer']['id']}") ?> 
[<?php echo $cancelled_project['Website']['uri'] ?>]:</em> Cancelled <?php echo $time->relativeTime($cancelled_project['Service']['cancelled']) ?></dd>
<?php endforeach; $current_customer='';?>
</dl>
</div>
<?php endif;?>

<?php if(!empty($other_projects)):?>
<div class="other_projects_list project_list">
<h3 class="collapse_hook">Other Projects [<?php echo count($other_projects);?>]</h3>
<dl class="collapse">
<?php $current_customer=''; foreach($other_projects as $i=>$other_project):?>
<?php if($current_customer!=$other_project['Customer']['id']):?>
<dt><?php echo $textAssistant->sanitiseText($other_project['Customer']['company_name']);?></dt>
<?php $current_customer = $other_project['Customer']['id'];?>
<?php endif; ?>
<dd><em><?php echo $html->link($other_project['Service']['title'],"/customers/view/{$other_project['Customer']['id']}") ?> 
[<?php echo $other_project['Website']['uri'] ?>]:</em> <?php echo ($other_project['Service']['status']==SERVICE_STATUS_COMPLETE)?"Completed":"Set as Pending" ?> <?php echo $time->relativeTime($other_project['Service']['modified']) ?></dd>
<?php endforeach; $current_customer='';?>
</dl>
</div>
<?php endif; ?>
</div>