<?php 
class AppController extends Controller {
	var $uses = array('User');
	var $menuOptions = array('controllerButton'=>false);
	var $helpers = array('Status','Html','Form','Time','TextAssistant','Javascript','Session');
	var $components = array(
		'Acl',
		'Auth',
		'Dux',
		'RequestHandler',
		'Session',
		'AclMenu.Menu'
	);
	var $view = 'Theme';
	
	function beforeRender() {
		if($external_links = Configure::read('Dux.external_links')) $this->set('external_links',$external_links);
	}
	
	function beforeFilter() {
		if(isset($this->params['alt_content']) && $this->params['alt_content']=='Ajax') {
			$this->ajaxGetToPost();
		}
		$this->retrieveGetIdsToData();
		return true;
	}
	
	function ajaxGetToPost() {
		if(!empty($this->params['url']['customer_id'])) $this->customer_id = $this->params['url']['customer_id'];
		if(!empty($this->params['url']['data'])) $this->data = $this->params['url']['data'];
	}
	
	function retrieveGetIdsToData() {
		if(!empty($this->params['url']['data'])) {
			if(empty($this->data)) {
				$this->data = $this->params['url']['data'];
				unset($this->params['url']['data']);
			} else {
				$newdata = $this->params['url']['data'];
				$this->data = array_merge($newdata,$this->data);
				unset($this->params['url']['data']);
			}
		}
		foreach($this->params['url'] as $x=>$params) {
			if(preg_match('/_id$/i',$x)) {
				$newdata['Referrer'][$x] = $params;
				if(empty($this->data)) $this->data = array();
				$this->data = array_merge($newdata,$this->data);
			}
		}
	}

}
