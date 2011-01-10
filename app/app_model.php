<?php
class AppModel extends Model {	

	static $utf8IsSet = false;

	function beforeValidate() {
		$this->sanitiseData();
		return true;
	}

	function sanitiseData() {
		if(isset($this->data['description']) && !empty($this->data['description'])) {
			if(MOONLIGHT_ALLOW_HTML_IN_DESCRIPTIONS == false) 
				$this->data['description'] = strip_tags($this->data['description']);
			else
				$this->data['description'] = strip_tags($this->data['description'],MOONLIGHT_PERMITTED_HTML_ELEMENTS);
			$this->data['description'] = trim($this->data['description']);
		}
		if(isset($this->data['title']) && !empty($this->data['title'])) {
			$this->data['title'] = trim(strip_tags($this->data['title']));
		}
	}
		
	function swapFieldData($rowId1,$rowId2,$fieldname) {
		if( ($field1data = $this->field($fieldname,"{$this->name}.id=$rowId1")) &&
			($field2data = $this->field($fieldname,"{$this->name}.id=$rowId2")) )
				if( ($this->save(array("id"=>$rowId1,$fieldname=>$field2data))) &&
					($this->save(array("id"=>$rowId2,$fieldname=>$field1data))) )
					return true;
				else
					return false;
		else return false;
	}
}
?>