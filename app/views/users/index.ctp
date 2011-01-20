<div class="users index">
<h2><?php __('Users');?></h2>
<table>
<thead>
<tr>
<th>Id</th>
<th>Email</th>
<th>Password</th>
<th>New Password</th>
<th>Group</th>
<th>Created</th>
<th>Modified</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($users as $user): ?>
<tr>
<td><?php echo $user['User']['id']; ?>&nbsp;</td>
<td><?php echo $user['User']['email']; ?>&nbsp;</td>
<td><?php echo $user['User']['password']; ?>&nbsp;</td>
<td><?php echo $user['User']['password_a'] ?></td>
<td><?php echo $user['Group']['name']; ?>&nbsp;</td>
<td><?php echo $user['User']['created']; ?>&nbsp;</td>
<td><?php echo $user['User']['modified']; ?>&nbsp;</td>
<td class="actions">
<?php echo $this->Html->link(__('View', true), array('action' => 'view', $user['User']['id'])); ?>
<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $user['User']['id'])); ?>
<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $user['User']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $user['User']['id'])); ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<div class="actions">
<h3><?php __('Actions'); ?></h3>
<ul>
<li><?php echo $this->Html->link(__('New User', true), array('action' => 'add')); ?></li>
</ul>
</div>
