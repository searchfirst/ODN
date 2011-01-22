<?php
class DuxComponent extends Object {
	function initialize(&$controller, $settings = array()) {
		$this->controller =& $controller;
		$this->settings = $settings;
		$this->authInit();
	}

	function startup(&$controller) {
		$this->setTheme();
		$this->requestHandlerInit();
		$this->modelInit();
	}

	private function authInit() {
		$this->controller->Auth->loginError = "There was an error logging you in";
		$this->controller->Auth->authError = "You don't have permission to access this area. You may need to log in.";
		$this->controller->Auth->fields = array('username'=>'email','password'=>'password');
		$this->controller->Auth->actionPath = 'controllers/';
		$this->controller->Auth->authorize = 'actions';
		User::setCurrent($this->controller->Auth->user());
		$this->controller->set('current_user',User::getCurrent());
		$this->controller->set('currentUser',User::getCurrent());
	}

	private function setTheme() {
		if($theme = Configure::read('Dux.theme')) {
			$this->controller->theme = $theme;
		}
	}

	private function requestHandlerInit() {
		if(isset($this->controller->params['alt_content']) && $this->controller->params['alt_content']=='Rss')
			$this->controller->RequestHandler->renderAs($this->controller,'rss');
		if(isset($this->controller->params['alt_content']) && $this->controller->params['alt_content']=='Xml')
			$this->RequestHandler->renderAs($this->controller,'xml');
		if(isset($this->controller->params['alt_content']) && $this->controller->params['alt_content']=='Pdf') {
			$this->controller->RequestHandler->respondAs('application/pdf');
			$this->controller->RequestHandler->renderAs($this->controller,'pdf');
		}
		if($this->controller->RequestHandler->isAjax()) {
			$this->controller->set('is_ajax',true);
			$this->controller->set('isAjax',true);
		}
	}

	private function modelInit() {
		if(!empty($this->controller->primaryModel)) {
			$this->controller->set('primary_model',$this->controller->primaryModel);
			$this->controller->set('primaryModel',$this->controller->primaryModel);
		}
	}
}
