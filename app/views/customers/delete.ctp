<p>Do you really wish to delete this category?</p>
<?php echo $html->formTag("/categories/delete/$id") ?>
<?php echo $html->hidden('Category/id', array('value'=>$id)) ?>
<?php echo $html->submitTag('Yes, delete this category.') ?>
</form>