<?php
class DuxComponent extends Object {
	function initialize(&$controller, $settings = array()) {
		$this->controller =& $controller;
		$this->settings = $settings;
		$this->authInit();
		$this->minifyInit();
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

	private function minifyInit() {
		$js_list = array(
			'js/jquery/libs/simplemodal.js','js/jquery/libs/bgiframe.js','js/jquery/libs/position_by.js','js/jquery/libs/jd_menu.js',
			'js/jquery/libs/more_less.js','js/jquery/libs/csv.js','js/jquery/libs/table.js','js/jquery/libs/hook_menu.js','js/jquery/libs/dux_tabs.js',
			'js/jquery/libs/hook_pagination.js','js/jquery/libs/form_entry_sanity.js','js/modal_config.js','js/filter_config.js','js/load_config.js'
		);
		$css_list = array(
			'css/reset.css','css/type.css','css/default.css','css/framework.css','css/tablets_netbooks.css','css/desktop.css',
			'css/print.css','css/widgets/tabs.css','css/widgets/lists.css','css/widgets/modal.css','css/widgets/hook_menu.css',
			'css/widgets/forms.css','css/widgets/flags.css'
		);
		if ($additional_js = Configure::read('Dux.additional_js')) {
			$js_list = array_merge($js_list, $additional_js);
		}
		if ($additional_css = Configure::read('Dux.additional_css')) {
			$css_list = array_merge($css_list, $additional_css);
		}
		$js = $this->controller->Minify->js($js_list);
		$css = $this->controller->Minify->css($css_list);
		$this->controller->set('minify',array('css'=>$css,'js'=>$js));
	}
}
