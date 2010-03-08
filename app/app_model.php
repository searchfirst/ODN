<?php
class AppModel extends Model {	

	static $utf8IsSet = false;

	function beforeValidate() {
		$this->sanitiseData();
		return true;
	}

/*	function beforeSave() {
		if( empty($this->id) && !isset($this->data[$this->name]['joined']) )
			$this->data[$this->name]['slug'] = $this->getUniqueSlug($this->data[$this->name]['title']);
		return true;
	}*/

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
	/* End of File Upload functions*/	
	
	function getUniqueSlug($string, $field="slug") {
		// Build URL
		$currentUrl = $this->_getStringAsSlug($string);
		// Look for same URL, if so try until we find a unique one
		
		$conditions = array($this->name . '.' . $field => 'LIKE ' . $currentUrl . '%');
		$result = $this->findAll($conditions, $this->name . '.*', null);
		
		if ($result !== false && count($result) > 0) {
			$sameUrls = array();
			foreach($result as $record)
			    $sameUrls[] = $record[$this->name][$field];
		}
		
		if(($this->name=='Resource') && isset($GLOBALS['MOONLIGHT_RESOURCE_PREV_SLUGS'])) {
			if(!isset($sameUrls)) $sameUrls = array();
			$sameUrls = array_merge($sameUrls,$GLOBALS['MOONLIGHT_RESOURCE_PREV_SLUGS']);
			}		
		
		if (isset($sameUrls) && count($sameUrls) > 0) {
			$currentBegginingUrl = $currentUrl;
			$currentIndex = 1;
		    while($currentIndex > 0) {
				if (!in_array($currentBegginingUrl . '-' . $currentIndex, $sameUrls)) {
				    $currentUrl = $currentBegginingUrl . '-' . $currentIndex;
				    $currentIndex = -1;
				}
				$currentIndex++;
			}
		}
		if($this->name=='Resource') $GLOBALS['MOONLIGHT_RESOURCE_PREV_SLUGS'][] = $currentUrl;
		return $currentUrl;
	}

	function _getStringAsSlug($string) {
		// Define the maximum number of characters allowed as part of the slug
		$currentMaximumSlugLength = 100;
				
		// Any non valid characters will be treated as _, also remove duplicate _
		$bad = array('Š','Ž','š','ž','Ÿ','À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ñ',
		'Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','à','á','â','ã','ä','å','ç','è','é','ê',
		'ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ',
		'Þ','þ','Ð','ð','ß','Œ','œ','Æ','æ','µ',
		'”',"'",'“','”',"\n","\r",'_');
		$good = array('S','Z','s','z','Y','A','A','A','A','A','A','C','E','E','E','E','I','I','I','I','N',
		'O','O','O','O','O','O','U','U','U','U','Y','a','a','a','a','a','a','c','e','e','e',
		'e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y',
		'TH','th','DH','dh','ss','OE','oe','AE','ae','u',
		'','','','','','','-');
		$string = trim(str_replace($bad, $good, $string));
		
		$bad_reg = array('/\s+/','/[^A-Za-z0-9\-]/');
		$good_reg = array('-','');
		$string = preg_replace($bad_reg, $good_reg, $string);

		// Cut at a specified length
		if (strlen($string) > $currentMaximumSlugLength)
			$string = substr($string, 0, $currentMaximumSlugLength);
		
		// Remove beggining and ending signs
		$string = preg_replace('/_$/i', '', $string);
		$string = preg_replace('/^_/i', '', $string);
		
		$string = str_replace(array('----','---','--'),array('-','-','-'),$string);
		return strtolower($string);
	}
}
?>