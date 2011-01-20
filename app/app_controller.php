<?php 
class AppController extends Controller {
	var $uses = array('User');
	var $helpers = array('Status','Html','Form','Time','TextAssistant','Javascript','Session');
	var $components = array(
		'RequestHandler',
		'Session',
		'Acl',
		'Auth'
	);
	var $view = 'Theme';
	
	function beforeRender() {
		if(isset($this->params['alt_content']) && $this->params['alt_content']=='Rss')
			$this->RequestHandler->renderAs($this,'rss');
		if(isset($this->params['alt_content']) && $this->params['alt_content']=='Xml')
			$this->RequestHandler->renderAs($this,'xml');
		if(isset($this->params['alt_content']) && $this->params['alt_content']=='Pdf') {
			$this->RequestHandler->respondAs('application/pdf');
			$this->RequestHandler->renderAs($this,'pdf');
		}
		if($external_links = Configure::read('Dux.external_links')) $this->set('external_links',$external_links);
		if(isset($this->primaryModel)) $this->set('primary_model',$this->primaryModel);
		if($this->RequestHandler->isAjax()) $this->set('is_ajax',true);
		$this->log($this->Auth->user('role'));
	}
	
	function beforeFilter() {
		$this->Auth->loginError = "There was an error logging you in";
		$this->Auth->authError = "You don't have permission to access this area. You may need to log in.";
		$this->Auth->fields = array('username'=>'email','password'=>'password');
		$this->Auth->actionPath = 'controllers/';
		$this->Auth->authorize = 'actions';
		$this->setTheme();
		$this->User->setCurrent($this->Auth->user());
		$this->set('current_user',$this->Auth->user());
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

	function setTheme() {
		if($theme = Configure::read('Dux.theme')) {
			$this->theme = $theme;
		} else {
			
		}
	}
}
