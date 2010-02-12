<h2>List Resources</h2>

<table cellpadding="0" cellspacing="0">
<tr>
	<th>Id</th>
	<th>Title</th>
	<th>Slug</th>
	<th>Description</th>
	<th>Created</th>
	<th>Modified</th>
	<th>Image Id</th>
	<th>Actions</th>
</tr>
<?php foreach ($resources as $resource): ?>
<tr>
	<td><?php echo $resource['Resource']['id'] ?></td>
	<td><?php echo $resource['Resource']['title'] ?></td>
	<td><?php echo $resource['Resource']['slug'] ?></td>
	<td><?php echo $resource['Resource']['description'] ?></td>
	<td><?php echo $resource['Resource']['created'] ?></td>
	<td><?php echo $resource['Resource']['modified'] ?></td>
	<td><?php echo $resource['Resource']['image_id'] ?></td>
	<td>
		<?php echo $html->link('View','/resources/view/' . $resource['Resource']['id'])?>
		<?php echo $html->link('Edit','/resources/edit/' . $resource['Resource']['id'])?>
		<?php echo $html->link('Delete','/resources/delete/' . $resource['Resource']['id'], null, 'Are you sure you want to delete: id ' . $resource['Resource']['id'])?>
	</td>
</tr>
<?php endforeach; ?>
</table>

<ul class="actions">
	<li><?php echo $html->link('New Resource', '/resources/add'); ?></li>
</ul>
