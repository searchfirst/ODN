<?php
class DuxComponent extends Object {
	function initialize(&$controller, $settings = array()) {
		$this->controller =& $controller;
		$this->settings = $settings;
		$this->authInit();
		$this->commonRequestInfo = array(
			'isPost' => $this->controller->RequestHandler->isAjax(),
			'isPut' => $this->controller->RequestHandler->isPut(),
			'isAjax' => $this->controller->RequestHandler->isAjax()
		);
		if ($this->controller->RequestHandler->isAjax() && $this->controller->name != 'CakeError') {
			$this->getAjaxPutData();
		} else {
			$this->minifyInit();
		}
	}

	function startup(&$controller) {
		if (!$this->controller->RequestHandler->isAjax()) {
			$this->setTheme();
			$this->modelInit();
		}
	}

	function beforeRender(&$controller) {
		$this->mergeGetData();
		$this->requestHandlerInit();
	}

	function getAjaxPutData() {
		if ($this->controller->RequestHandler->isPost() || $this->controller->RequestHandler->isPut()) {
			$modelClass = $this->controller->modelClass;
			$data = json_decode(file_get_contents("php://input"),true);
			if ($data && !array_key_exists($modelClass,$data)) {
				$data[$modelClass] = array();
				foreach ($data as $key => $val) {
					if (!preg_match('/^[A-Z]{1}/',$key)) {
						if (!($key == 'created' && $key == 'modified')) {
							$data[$modelClass][$key] = $val;
						}
						unset($data[$key]);
					}
				}
			}
			$this->controller->data = $data;
		}
	}

	function commonRequestInfo() {
		return $this->commonRequestInfo;
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
				'js/libs/modernizr.js','js/libs/yepnope.min.js'
			),
			'foot' => array(
				'js/jquery/libs/hook_menu.js',
				'js/jquery/libs/dux_tabs.js',
				'js/jquery/libs/editable.js',
				'js/jquery/libs/selectable.js',
				'js/jquery/libs/collapse.js',
				'js/jquery/libs/timepicker/jquery-ui-timepicker-addon.js',
				'js/libs/linen/linen.js',
				'js/libs/underscore.js',
				//'js/libs/backbone.js',
				//'js/libs/backbone-ps.js',
				'js/libs/backbone/backbone.js',
				'js/libs/paginatedcollection.backbone.js',
				'js/libs/handlebars.min.js',
				'js/libs/gruft/src/gruft-common.js',
				'js/libs/gruft/src/gruft-md5.js',
				'js/app/cache.js',
				'js/app/templates.js',
				'js/app/classes.js',
				'js/app/models/customer.js',
				'js/app/models/contact.js',
				'js/app/models/service.js',
				'js/app/models/website.js',
				'js/app/models/user.js',
				'js/app/models/note.js',
				'js/app/models/invoice.js',
				'js/app/models/facade.js',
				'js/app/models/schedule.js',
				'js/app/views/customers.js',
				'js/app/views/contacts.js',
				'js/app/views/services.js',
				'js/app/views/websites.js',
				'js/app/views/users.js',
				'js/app/views/notes.js',
				'js/app/views/invoices.js',
				'js/app/views/facades.js',
				'js/app/views/schedules.js',
				'js/app/routers/customers.js',
				'js/app/routers/contacts.js',
				'js/app/routers/services.js',
				'js/app/routers/websites.js',
				'js/app/routers/users.js',
				'js/app/routers/notes.js',
				'js/app/routers/invoices.js',
				'js/app/routers/facades.js',
				'js/app/routers/schedules.js',
				'js/app/app.js'
			)
		);
		$template_list = array(
			'core' => array(
				'file_list' => array(
					'facades_index' => 'js/app/templates/facades/index.mustache',
					'customersIndex' => 'js/app/templates/customers/index.mustache',
					'customersView' => 'js/app/templates/customers/view.mustache',
					'customersAdd' => 'js/app/templates/customers/add.mustache',
					'customerItemView' => 'js/app/templates/customers/view_item.mustache',
					'customerItemAdd' => 'js/app/templates/customers/add_item.mustache',
					'customerButtons' => 'js/app/templates/customers/buttons.mustache',
					'invoicesView' => 'js/app/templates/invoices/view.mustache',
					'invoiceItemView' => 'js/app/templates/invoices/view_item.mustache',
					'invoiceItemAdd' => 'js/app/templates/invoices/add_item.mustache',
					'invoiceButtons' => 'js/app/templates/invoices/buttons.mustache',
					'noteItemView' => 'js/app/templates/notes/view_item.mustache',
					'contactItemView' => 'js/app/templates/contacts/view_item.mustache',
					'contactItemAdd' => 'js/app/templates/contacts/add_item.mustache',
					'contactButtons' => 'js/app/templates/contacts/buttons.mustache',
					'serviceItemView' => 'js/app/templates/services/view_item.mustache',
					'serviceItemAdd' => 'js/app/templates/services/add_item.mustache',
					'serviceButtons' => 'js/app/templates/services/buttons.mustache',
					'websiteItemView' => 'js/app/templates/websites/view_item.mustache',
					'websiteItemAdd' => 'js/app/templates/websites/add_item.mustache',
					'websiteButtons' => 'js/app/templates/websites/buttons.mustache',
					'pagination' => 'js/app/templates/elements/pagination.mustache',
					'emptyCollection' => 'js/app/templates/elements/empty_collection.mustache',
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
					'customersViewWebsites' => 'js/app/templates/customers/view/websites.mustache',
					'customersViewCustomers' => 'js/app/templates/customers/view/customers.mustache'
				),
				'variable' => 'hb_partials',
				'post_commands' => '_(hb_partials).each(function(value,key){CnrsTemplates.addPartial(key,value)});'
			)
		);
		$css_list = array(
			'css/reset.css','css/type.css','css/default.css','css/framework.css','css/tablets_netbooks.css','css/desktop.css',
			'css/print.css','css/widgets/tabs.css','css/widgets/lists.css','css/widgets/modal.css','css/widgets/hook_menu.css',
			'css/widgets/forms.css','css/widgets/flags.css','css/widgets/dialog.css','css/widgets/loading.css','css/widgets/pagination.css',
			'css/widgets/editable.css','css/widgets/selectable.css','css/widgets/collapse.css','css/ui/jquery-ui-1.8.11.custom.css'
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
