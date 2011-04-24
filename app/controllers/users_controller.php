<?php
class UsersController extends AppController {

	var $name = 'Users';
	var $primaryModel = 'User';
	var $paginate = array(
		'limit' => 50,
		'order' => array('User.name' => 'ASC'),
		'recursive' => 0
	);

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login','logout');
		return true;
	}
	
	function index() {
		$paginationOptions = array();
		$doPaginate = !(isset($this->params['url']['limit']) && $this->params['url']['limit'] == 'all');
		if ($this->RequestHandler->isAjax()) { $this->paginate['limit'] = 10; }
		if ($doPaginate) {
			$users = $this->paginate('User');
		} else {
			$this->User->recursive = 0;
			$User = $this->User->find('all');
		}
		$this->set('doPaginate',$doPaginate);
		$this->set('users', $users);
		$this->set('title_for_layout','Users');
	}

	function view($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid Employee');
			$this->redirect($this->referer('/'));
		}
		if($user=$this->User->findById($id)) {
			$this->set('user',$user);
		} else {
			
		}
	}

	function add() {
		extract($this->Dux->commonRequestInfo());
		if ($isPost) {
			if (!$isAjax) {
				if ($this->User->saveAll($this->data)) {
					$this->Session->setFlash(__("User created successfully.",true));
					$this->redirect(array('controller'=>'users','action'=>'view',$this->id));
				} else {
					$this->Session->setFlash(__("Please correct errors below.",true));
				}
			} else {
				if ($this->User->save(array('User'=>$this->data['User']))) {
					$this->set('model', $this->User->readRoot());
				} else {
					$this->cakeError('ajaxError',array('message'=>'Not saved'));
				}
			}
		} else {
			if (!empty($this->passedArgs['customer_id'])) {
				$this->data['User']['customer_id'] = $this->passedArgs['customer_id'];
			} else {
				$this->cakeError('missingId',array('model'=>'Customer'));
			}
		}
	}

	function edit($id = null) {
		extract($this->Dux->commonRequestInfo());
		if(!$id) {
			$this->cakeError('missingId',array('model'=>'User'));
		}
		$this->User->id = $id;
		$this->User->recursive = -1;

		if (!($isPost || $isPut)) {
			$this->data = $this->User->read();
		} else {
			if (!isAjax) {
				if($this->User->save($this->data)) {
					$this->Session->setFlash("User saved successfully.");
					$this->redirect(array('action'=>'view',$id));
				} else {
					$this->Session->setFlash('Please correct errors below.');
				}
			} else {
				if ($this->User->save(array('User' => $this->data['User']))) {
					$this->set('model',$this->User->readRoot());
				} else {
					$this->cakeError('ajaxError',array('message'=>'Not saved'));
				}
			}
		}
	}

	function delete($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid Employee');
			$this->redirect($this->referer('/'));
		}
		if( ($this->data['Customer']['id']==$id) && ($this->Customer->del($id)) ) {
			$this->Session->setFlash('Customer successfully deleted');
			$this->redirect($this->referer('/'));
		} else {
			$this->set('id',$id);
		}
	}

	function authenticate() {
		if(isset($this->data)) {
			if($auth_user=$this->User->authenticate($this->data)) {
				$this->Session->write('User.data',array('User'=>array('id'=>$auth_user['User']['id'],'hash'=>md5($auth_user['User']['password']))));
				$this->Session->setFlash('Authentication succeeded');
				$this->redirect($this->referer('/'));
			} else {
				$this->Session->setFlash('Authentication failed');
				$this->redirect($this->referer('/'));
			}
		}
	}
	
	function login() {
		if ($this->Session->read('Auth.User')) {
			$this->Session->setFlash('You have been logged in.');
			$this->redirect($this->Auth->redirect(), null, false);
		}
	}       

	function logout() {
		$this->Session->setFlash('Good-Bye');
		$this->redirect($this->Auth->logout());
	}


	function build_acl() {
		if (!Configure::read('debug')) {
			return $this->_stop();
		}
		$log = array();

		$aco =& $this->Acl->Aco;
		$root = $aco->node('controllers');
		if (!$root) {
			$aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
			$root = $aco->save();
			$root['Aco']['id'] = $aco->id; 
			$log[] = 'Created Aco node for controllers';
		} else {
			$root = $root[0];
		}   

		App::import('Core', 'File');
		$Controllers = Configure::listObjects('controller');
		$appIndex = array_search('App', $Controllers);
		if ($appIndex !== false ) {
			unset($Controllers[$appIndex]);
		}
		$baseMethods = get_class_methods('Controller');
		$baseMethods[] = 'buildAcl';

		$Plugins = $this->_getPluginControllerNames();
		$Controllers = array_merge($Controllers, $Plugins);

		// look at each controller in app/controllers
		foreach ($Controllers as $ctrlName) {
			$methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

			// Do all Plugins First
			if ($this->_isPlugin($ctrlName)){
				$pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
				if (!$pluginNode) {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
					$pluginNode = $aco->save();
					$pluginNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
				}
			}
			// find / make controller node
			$controllerNode = $aco->node('controllers/'.$ctrlName);
			if (!$controllerNode) {
				if ($this->_isPlugin($ctrlName)){
					$pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
					$aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
				} else {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $ctrlName;
				}
			} else {
				$controllerNode = $controllerNode[0];
			}

			//clean the methods. to remove those in Controller and private actions.
			foreach ($methods as $k => $method) {
				if (strpos($method, '_', 0) === 0) {
					unset($methods[$k]);
					continue;
				}
				if (in_array($method, $baseMethods)) {
					unset($methods[$k]);
					continue;
				}
				$methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
				if (!$methodNode) {
					$aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
					$methodNode = $aco->save();
					$log[] = 'Created Aco node for '. $method;
				}
			}
		}
		if(count($log)>0) {
			debug($log);
		}
	}

	function _getClassMethods($ctrlName = null) {
		App::import('Controller', $ctrlName);
		if (strlen(strstr($ctrlName, '.')) > 0) {
			// plugin's controller
			$num = strpos($ctrlName, '.');
			$ctrlName = substr($ctrlName, $num+1);
		}
		$ctrlclass = $ctrlName . 'Controller';
		$methods = get_class_methods($ctrlclass);

		// Add scaffold defaults if scaffolds are being used
		$properties = get_class_vars($ctrlclass);
		if (array_key_exists('scaffold',$properties)) {
			if($properties['scaffold'] == 'admin') {
				$methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
			} else {
				$methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
			}
		}
		return $methods;
	}

	function _isPlugin($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) > 1) {
			return true;
		} else {
			return false;
		}
	}

	function _getPluginControllerPath($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0] . '.' . $arr[1];
		} else {
			return $arr[0];
		}
	}

	function _getPluginName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0];
		} else {
			return false;
		}
	}

	function _getPluginControllerName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[1];
		} else {
			return false;
		}
	}

/**
 * Get the names of the plugin controllers ...
 * 
 * This function will get an array of the plugin controller names, and
 * also makes sure the controllers are available for us to get the 
 * method names by doing an App::import for each plugin controller.
 *
 * @return array of plugin names.
 *
 */
	function _getPluginControllerNames() {
		App::import('Core', 'File', 'Folder');
		$paths = Configure::getInstance();
		$folder =& new Folder();
		$folder->cd(APP . 'plugins');

		// Get the list of plugins
		$Plugins = $folder->read();
		$Plugins = $Plugins[0];
		$arr = array();

		// Loop through the plugins
		foreach($Plugins as $pluginName) {
			// Change directory to the plugin
			$didCD = $folder->cd(APP . 'plugins'. DS . $pluginName . DS . 'controllers');
			// Get a list of the files that have a file name that ends
			// with controller.php
			$files = $folder->findRecursive('.*_controller\.php');

			// Loop through the controllers we found in the plugins directory
			foreach($files as $fileName) {
				// Get the base file name
				$file = basename($fileName);

				// Get the controller name
				$file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
				if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
					if (!App::import('Controller', $pluginName.'.'.$file)) {
						debug('Error importing '.$file.' for plugin '.$pluginName);
					} else {
						/// Now prepend the Plugin name ...
						// This is required to allow us to fetch the method names.
						$arr[] = Inflector::humanize($pluginName) . "/" . $file;
					}
				}
			}
		}
		return $arr;
	}


function initDB() {
//		$groups = $this->User->Group->find('all');
//		foreach($groups as $g) {
//			$this->save_aro($group);
//		}
    $group =& $this->User->Group;
    //Allow admins to everything
    $group->id = 1;     
		//$this->Acl->allow(array( 'model' => 'Group', 'foreign_key' => 1), 'controllers');
		$this->Acl->allow($group, 'controllers');
 
    //allow managers to posts and widgets
/*    $group->id = 2;
    $this->Acl->deny($group, 'controllers');
    $this->Acl->allow($group, 'controllers/Posts');
    $this->Acl->allow($group, 'controllers/Widgets');
*/ 
    //allow users to only add and edit on posts and widgets
/*    $group->id = 3;
    $this->Acl->deny($group, 'controllers');        
    $this->Acl->allow($group, 'controllers/Posts/add');
    $this->Acl->allow($group, 'controllers/Posts/edit');        
    $this->Acl->allow($group, 'controllers/Widgets/add');
    $this->Acl->allow($group, 'controllers/Widgets/edit');*/
}


}
