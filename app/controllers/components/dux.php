<?php
class DuxComponent extends Object {
	function initialize(&$controller, $settings = array()) {
		$this->controller =& $controller;
		$this->settings = $settings;
		$this->authInit();
		$this->minifyInit();
	}

	function startup(&$controller) {
		if (!$this->controller->RequestHandler->isAjax()) {
			$this->setTheme();
			$this->modelInit();
		}
		$this->requestHandlerInit();
	}

	function beforeRender(&$controller) {
		$this->mergeGetData();
	}

	function renderAjax($json_object=array(),$controllerViewPath=false) {
		Configure::write('debug', 0);
		$this->controller->RequestHandler->renderAs($this->controller,'json');
		$this->controller->RequestHandler->respondAs('json');
		$this->controller->set('json_object',$json_object);
		if (!$controllerViewPath) {
			$this->controller->viewPath = 'json';
			$this->controller->render('index');
		} else {
			$this->controller->render($this->controller->action);
		}
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
		if($this->controller->RequestHandler->isAjax()) {
			$this->controller->set('isAjax',true);
			$this->controller->RequestHandler->renderAs($this->controller,'json');
			$this->controller->RequestHandler->respondAs('json');
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
			'head' => array(
				'js/libs/modernizr.js','jly'=>'js/libs/yepnope.min.js'
			),
			'foot' => array(
				'jll'=>'js/libs/linen.min.js',
				'js/jquery/jquery-ui-1.8.9.custom.js','js/jquery/libs/hook_menu.js','js/jquery/libs/dux_tabs.js',
				'js/jquery/libs/hook_pagination.js','js/load_config.js','js/libs/underscore.js','js/libs/backbone.js',
				'js/libs/backbone-ps.js','js/libs/paginatedcollection.backbone.js','jlh'=>'js/libs/handlebars.min.js'
			)
		);
		$template_list = array(
			'core' => array(
				'file_list' => array(
					'facades_index' => 'js/app/templates/facades/index.mustache',
					'customersView' => 'js/app/templates/customers/view.mustache'
				),
				'variable' => 'hb_templates',
				'post_commands' => '_(hb_templates).each(function(value,key){CnrsTemplates.add(key,value)});'
			),
			'partials' => array(
				'file_list' => array(
					'facades_projects' => 'js/app/templates/facades/index/projects.mustache',
					'facades_notes' => 'js/app/templates/facades/index/notes.mustache',
					'customersViewDetails' => 'js/app/templates/customers/view/details.mustache',
					'customersViewInvoices' => 'js/app/templates/customers/view/invoices.mustache',
					'customersViewNotes' => 'js/app/templates/customers/view/notes.mustache',
					'customersViewServices' => 'js/app/templates/customers/view/services.mustache',
					'customersViewCustomers' => 'js/app/templates/customers/view/customers.mustache'
				),
				'variable' => 'hb_partials',
				'post_commands' => '_(hb_partials).each(function(value,key){CnrsTemplates.addPartial(key,value)});'
			)
		);
		$css_list = array(
			'css/reset.css','css/type.css','css/default.css','css/framework.css','css/tablets_netbooks.css','css/desktop.css',
			'css/print.css','css/widgets/tabs.css','css/widgets/lists.css','css/widgets/modal.css','css/widgets/hook_menu.css',
			'css/widgets/forms.css','css/widgets/flags.css','css/widgets/dialog.css'
		);
		if ($additional_js = Configure::read('Dux.additional_js')) {
			$js_list['foot'] = array_merge($js_list['foot'], $additional_js);
		}
		if ($additional_css = Configure::read('Dux.additional_css')) {
			$css_list = array_merge($css_list, $additional_css);
		}
		$js = array();
		foreach ($js_list as $x=>$j) {
			$js[$x] = $this->controller->Minify->js($j);
		}
		$tpl = $this->controller->Minify->js_tpl($template_list);
		$css = $this->controller->Minify->css($css_list);
		$this->controller->set('minify',array('css'=>$css,'js'=>$js,'tpl'=>$tpl));
	}

	function mergeGetData() {
		if(!empty($this->controller->params['url']['data'])) {
			if(empty($this->controller->data)) {
				$this->controller->data = $this->controller->params['url']['data'];
			} else {
				$this->controller->data = array_merge($this->controller->params['url']['data'],$this->controller->data);
			}
			unset($this->controller->params['url']['data']);
		}
	}
}
