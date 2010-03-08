<?php
if(!isset($model)) $model = $this->modelNames[0];
if(!isset($controller)) $controller = $this->name;
?>
<form method="post" action="<?php echo $html->url("/".Inflector::underscore($controller)."/change_status/$id")?>" id="modify_status_form">
<button name="data[Service][submit]" id="ModifyStatusButton" value="" title="Modify Status" class="ModifyStatusButton">
<img src="<?php echo $this->webroot ?>img/edit-icon.png" alt="" /> Change <?php echo $model ?> Status
</button>
<a href="/ajax/services/change_status/<?php echo $id ?>?height=400&amp;width=600" class="skip thickbox" title="Modify Status">Modify Status Details</a>
</form>