<h2>List of Products</h2>

<table cellpadding="0" cellspacing="0">
<thead><tr>
<th>Product Title</th>
<th class="ancillary">Category</th>
</tr></thead>
<tfoot><tr><td colspan="3"><?php echo $html->formTag('/products/add','get')?>
<?php echo $html->submitTag('New Product')?>
</form>
</td></tr></tfoot>
<tbody>
<?php foreach ($products as $product):?>
<tr>
<td>
<div class="options">
<?php echo $this->element('edit_form',array('id'=>$product['Product']['id'],'title'=>$product['Product']['title']))?>
<?php echo $this->element('delete_form',array('id'=>$product['Product']['id'],'title'=>$product['Product']['title']))?> 
</div>
<?php echo $html->link($product['Product']['title'],'/products/view/' . $product['Product']['id'])?>
</td>
<td><?php echo $html->link($product['Category']['title'],"/categories/view/{$product['Category']['id']}") ?></td>
</tr>
<?php endforeach;?>
</tbody>
</table>
