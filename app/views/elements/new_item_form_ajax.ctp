<?php
if(!isset($model)) $model = $primary_model;
if(!isset($controller)) $controller = $this->name;
?>
<form method="post" action="<?php echo $html->url("/".Inflector::underscore($controller)."/add")?>?height=300&amp;width=300">
<?php echo $form->hidden("Referrer.".Inflector::underscore($parentClass)."_id",array('value'=>$parentId))?> 
<?php
	if(isset($website)) echo $form->hidden("Referrer.website_id",array('value'=>$website));
?> 
<button name="data[<?php echo"$model" ?>][submit]" value="">
<img src="<?php echo $this->webroot ?>img/new-item-icon.png" alt="" /> Add <?php echo $model ?>
</button>
<a href="/ajax/invoices/raise?height=400&amp;width=600&amp;customer_id=<?php echo $id ?>" class="skip thickbox">Add <?php echo $model ?></a>
</form>
