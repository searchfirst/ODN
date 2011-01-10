<?php 
class AppController extends Controller {
	var $uses = array('User');
	var $helpers = array('Status','Html','Form','Time','TextAssistant','Javascript');
	var $components = array('RequestHandler','Session');
	var $permissions = array(
		"index"=>array(
			'owner'=>null,
			'admin'=>array('group'=>array('Admin'),'conditions'=>null),
			'group'=>array('group'=>array(),'conditions'=>null),
			'other'=>array('group'=>array(),'conditions'=>null)
		),
		"view"=>array(
			'owner'=>array('owner_conditions','conditions'=>null),
			'admin'=>array('group'=>array('Admin'),'conditions'=>null),
			'group'=>array('group'=>array(),'conditions'=>null),
			'other'=>array('group','conditions'=>null)
		),
		"edit"=>array(
			'owner'=>array('owner_conditions','conditions'=>null),
			'admin'=>array('group'=>array('Admin'),'conditions'=>null),
			'group'=>array('group'=>array(),'conditions'=>null),
			'other'=>array('group','conditions'=>null)
		),
		"add"=>array(
			'owner'=>array('owner_conditions','conditions'=>null),
			'admin'=>array('group'=>array('Admin'),'conditions'=>null),
			'group'=>array('group'=>array(),'conditions'=>null),
			'other'=>array('group','conditions'=>null)
		),
		"delete"=>array(
			'owner'=>array('owner_conditions','conditions'=>null),
			'admin'=>array('group'=>array('Admin'),'conditions'=>null),
			'group'=>array('group'=>array(),'conditions'=>null),
			'other'=>null
		)
	);
	var $permissionsStatus = array('admin'=>false,'owner'=>false,'group'=>false,'other'=>false);
	
	function beforeRender() {
		$this->set('permissions_status',$this->permissionsStatus);
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
	}
	
	function beforeFilter() {
		/* Making use of global var at this stage until I'm sure that things will be smoothly handled */
		global $current_user;
		$current_user = $this->User->getCurrent($this->Session->read('User.data'));
		$this->current_user = &$current_user;
		if(!$this->current_user['User']) {
			$this->viewPath = 'users';
			$this->render('authenticate');
		} else {
			$this->set('current_user',$this->current_user);
		}
		if(isset($this->params['alt_content']) && $this->params['alt_content']=='Ajax') {
			$this->ajaxGetToPost();
		}
		$this->retrieveGetIdsToData();
		return true;
	}
		
	function generateConditions(&$model=null,$action=null,$permissions=null,$user=null,$callback=null) {
		if(!$action) $action=$this->action;
		if(!$permissions) $permissions=$this->permissions;
		if(!$user) $user=&$this->current_user;
		if(empty($callback)) $callback='find';
		//owner
		if(!$this->permissions[$action]['owner'])
			$this->permissionsStatus['owner'] = false;
		else
			if(call_user_func(array(&$model,$callback),$this->permissions[$action]['owner']['owner_conditions'])) $this->permissionsStatus['owner']=true;
			//if($model->find($this->permissions[$action]['owner']['owner_conditions']))
		//admin
		if(!$this->permissions[$action]['admin']) $this->permissionsStatus['admin'] = false;
		else {
			$user_groups = $user['Group'];
			if(is_array($user_groups))
				foreach($user_groups as $user_group)
					if(in_array($user_group['name'],$this->permissions[$action]['admin']['group'])) $this->permissionsStatus['admin'] = true;
		}
		//group
		if(!$this->permissions[$action]['group']) $this->permissionsStatus['owner'] = false;
		else {
			$user_groups = $user['Group'];
			if(is_array($user_groups))
				foreach($user_groups as $user_group)
					if(in_array($user_group['name'],$this->permissions[$action]['group']['group'])) $this->permissionsStatus['group'] = true;
		}
		//other
		if(!$this->permissions[$action]['other']) $this->permissionsStatus['owner'] = false;
		else {
			$user_groups = $user['Group'];
			if(is_array($user_groups) && count($user_groups))
				foreach($user_groups as $user_group)
					if(in_array($user_group['name'],$this->permissions[$action]['other']['group'])) $this->permissionsStatus['other'] = true;
		}
		if($this->permissionsStatus['owner']) return $this->permissions[$action]['owner']['conditions'];
		elseif($this->permissionsStatus['admin']) return $this->permissions[$action]['admin']['conditions'];
		elseif($this->permissionsStatus['group']) return $this->permissions[$action]['group']['conditions'];
		elseif($this->permissionsStatus['other']) return $this->permissions[$action]['other']['conditions'];
		else return null;
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
?>
