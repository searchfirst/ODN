<h2>Customers</h2>
<ul class="tab_hooks">
<li><a href="#active_projects">Active <?php echo !empty($active_projects)?"[".count($active_projects)."]":'[0]'?></a></li>
<li><a href="#cancelled_projects">Cancelled <?php echo !empty($cancelled_projects)?"[".count($cancelled_projects)."]":'[0]'?></a></li>
<li><a href="#other_projects">Other <?php echo !empty($other_projects)?"[".count($other_projects)."]":'[0]'?></a></li>
</ul>
<div id="active_projects" class="tab_page active_projects_list project_list">
<?php if(!empty($active_projects)):?>
<h3>Active Projects [<?php echo count($active_projects);?>]</h3>
<table>
<thead>
<tr><th>Service</th><th>Location</th><th class="date">Start Date</th></tr>
<tr><th colspan="3"><input type="search" class="filter" autosave="co.uk.searchfirst.dux.projects" results="10" placeholder="Filter Services"></th></tr>
</thead>
<tbody>
<?php $current_customer=''; foreach($active_projects as $i=>$active_project):?>
<?php if($current_customer!=$active_project['Customer']['id']):?>
<tr><th colspan="3"><?php echo $textAssistant->link($active_project['Customer']['company_name'],"/customers/view/{$active_project['Customer']['id']}");?></th></tr>
<?php $current_customer = $active_project['Customer']['id'];?>
<?php endif; ?>
<tr>
<td><?php echo $textAssistant->link($active_project['Service']['title'],"/services/view/{$active_project['Service']['id']}",array('class'=>'modalAJAX')) ?></td>
<td><?php echo $active_project['Website']['uri'] ?></td>
<td><?php echo substr($time->niceShort($active_project['Service']['joined']),0,-7) ?></td>
</tr>
<?php endforeach; $current_customer='';?>
</tbody>
</table>
<?php endif;?>
</div>

<div id="cancelled_projects" class="tab_page cancelled_projects_list project_list">
<?php if(!empty($cancelled_projects)):?>
<h3>Cancelled Projects [<?php echo count($cancelled_projects);?>]</h3>
<table>
<thead>
<tr><th>Service</th><th>Location</th><th class="date">Cancel Date</th></tr>
<tr><th colspan="3"><input type="search" class="filter" autosave="co.uk.searchfirst.dux.projects" results="10" placeholder="Filter Services"></th></tr>
</thead>
<tbody>
<?php $current_customer=''; foreach($cancelled_projects as $i=>$cancelled_project):?>
<?php if($current_customer!=$cancelled_project['Customer']['id']):?>
<tr><th colspan="3"><?php echo $textAssistant->link($cancelled_project['Customer']['company_name'],"/customers/view/{$cancelled_project['Customer']['id']}");?></th></tr>
<?php $current_customer = $cancelled_project['Customer']['id'];?>
<?php endif; ?>
<tr>
<td><?php echo $textAssistant->link($cancelled_project['Service']['title'],"/services/view/{$cancelled_project['Service']['id']}",array('class'=>'modalAJAX')) ?></td>
<td><?php echo $cancelled_project['Website']['uri'] ?></td>
<td><?php echo substr($time->niceShort($cancelled_project['Service']['cancelled']),0,-7) ?></td>
</tr>
<?php endforeach; $current_customer='';?>
</tbody>
</table>
<?php endif ?>
</div>

<div id="other_projects" class="tab_page other_projects_list project list">
<?php if(!empty($other_projects)):?>
<h3>Other Projects [<?php echo count($other_projects);?>]</h3>
<table>
<thead>
<tr><th>Service</th><th>Location</th><th class="date">Date</th></tr>
<tr><th colspan="3"><input type="search" class="filter" autosave="co.uk.searchfirst.dux.projects" results="10" placeholder="Filter Services"></th></tr>
</thead>
<tbody>
<?php $current_customer=''; foreach($other_projects as $i=>$other_project):?>
<?php if($current_customer!=$other_project['Customer']['id']):?>
<tr><th colspan="3"><?php echo $textAssistant->link($other_project['Customer']['company_name'],"/customers/view/{$other_project['Customer']['id']}");?></th></tr>
<?php $current_customer = $other_project['Customer']['id'];?>
<?php endif; ?>
<tr>
<td><?php echo $textAssistant->link($other_project['Service']['title'],"/services/view/{$other_project['Service']['id']}",array('class'=>'modalAJAX'))?></td>
<td><?php echo $other_project['Website']['uri'] ?></td>
<td><?php echo ($other_project['Service']['status']==SERVICE_STATUS_COMPLETE)?"Completed":"Set as Pending" ?> <?php echo $time->relativeTime($other_project['Service']['modified']) ?></td>
</tr>
<?php endforeach; $current_customer='';?>
</tbody>
</table>
<?php endif; ?>
</div>