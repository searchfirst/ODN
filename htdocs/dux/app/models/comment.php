<?php
class Comment extends AppModel {
	var $useDbConfig = MOONLIGHT_DB_CONFIG; //Remove this when moving to production
	var $name = 'Comment';

	var $belongsTo = array("Article");
	
	function beforeDelete() {
		return true;
	}
	
	function deleteMany($conditions) {
		if(empty($conditions)) return false;
		$delete_list = $this->generateList($conditions,null,null,'{n}.Comment.id','{n}.Comment.author');
		if(!empty($delete_list))
			foreach(array_keys($delete_list) as $item)
				$this->del($item);
		return true;
	}
}
?>