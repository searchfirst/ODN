<ul class="resource_list">
<?php foreach($resources as $resource):?>
<li>
<?php echo $this->renderElement('delete_form',array('id'=>$resource['id'],'model'=>'Resource','controller'=>'Resources','title'=>$resource['title']))?> 
<?php echo $mediaAssistant->downloadResourceLink($resource)?> 
</li>
<?php endforeach;?>
</ul>
