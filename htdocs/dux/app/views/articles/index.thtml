<h2>List Articles</h2>

<table>
<thead><tr>
<th>Title</th>
<th class="ancillary">Section</th>
</tr></thead>
<tfoot><tr><td colspan="2"><form method="post" action="<?php print $html->url('/articles/add')?>">	
<?php print $form->submit('New Article',array('label'=>false,'div'=>false))?></form></td></tr></tfoot>
<tbody>
<?php foreach ($articles as $article): ?>
<tr>
<td>
<div class="options">
<?php echo $this->renderElement('edit_form',array('id'=>$article['Article']['id'],'title'=>$article['Article']['title']))?> 
<?php echo $this->renderElement('delete_form',array('id'=>$article['Article']['id'],'title'=>$article['Article']['title']))?> 
</div>
<?php echo $html->link($article['Article']['title'],"/articles/view/{$article['Article']['id']}")?>
</td>
<td><?php echo $html->link($article['Section']['title'], "/sections/view/{$article['Section']['id']}")?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>