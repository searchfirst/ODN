<?php
class Article extends AppModel {
    var $name = 'Article';
	var $recursive = 1;

    var $validate = array(
	);
	
	function beforeSave() {
		if(empty($this->id))
			$this->data[$this->name]['slug'] = $this->getUniqueSlug($this->data[$this->name]['title']);
		return true;
	}
}
?>
