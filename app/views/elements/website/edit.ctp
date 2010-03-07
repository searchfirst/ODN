<?php
if(!isset($model)) $model = $this->modelNames[0];
if(!isset($controller)) $controller = $this->name;
?>
<form method="post" action="<?php echo $html->url("/".Inflector::underscore($controller)."/edit/$id")?>">
<button name="data[Service][submit]" id="ModifyStatusButton" value="" title="Modify Status" class="ModifyStatusButton">
<img src="<?php echo $this->webroot ?>img/edit-icon.png" alt="" /> Edit <?php echo $model ?>
</button>
<a href="/ajax/websites/edit/<?php echo $id ?>?height=400&amp;width=600" class="skip thickbox" title="Edit Website">Edit Website</a>
</form>