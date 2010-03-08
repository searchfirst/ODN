<?php
class UsersController extends AppController {

	var $name = 'Users';
	var $primaryModel = 'User';
	var $helpers = array('Javascript','Html','Form','Time','TextAssistant','MediaAssistant');

	function beforeFilter() {
		if($this->action!=='authenticate') parent::beforeFilter();
		return true;
	}
	
	function index() {
		$page = isset($this->params['page'])?strtoupper($this->params['page']):'all';
		$conditions = $this->generateConditions();
		if($page=='all') {
			$this->set('users', $this->User->findAll());
			$this->pageTitle = 'Employee List';
		} else {
			$this->set('users', $this->User->findAll(array('User.name'=>'LIKE '.$page.'%')));
			$this->pageTitle = "Employee List - $page";
		}
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
		if(empty($this->data)) {
			//$this->set('resellers', $this->Customer->Reseller->generateList());
			$this->set('customer_list',
				$this->Customer->generateList(array("OR"=>array('Customer.customer_id'=>'IS NULL','Customer.customer_id'=>0)),null,null,'{n}.Customer.id','{n}.Customer.company_name'));
			//$this->data['Referrer']['customer_id'] = 699;
			if(isset($this->data['Referrer']['customer_id'])) $this->set('customer',array('Customer'=>array('customer_id'=>$this->data['Referrer']['customer_id'])));
			else $this->set('customer',array('Customer'=>array('customer_id'=>null)));
		} else {
			if($this->Customer->save($this->data)) {
				$newcustomer = $this->Customer->getLastInsertId();
				if(!empty($this->data['Website']['uri'])) {
					$this->data['Website']['title'] = empty($this->data['Website']['title'])?$this->data['Customer']['company_name']:$this->data['Website']['title'];
					$this->data['Website']['customer_id'] = $newcustomer;
					$websitedata['Website'] = $this->data['Website'];
					$this->Customer->Website->save($websitedata);
				}
				$this->Session->setFlash("Customer created successfully.");
				$this->redirect("/".Inflector::underscore($this->name)."/view/$newcustomer");
			} else {
				$this->Session->setFlash('Please correct errors below.');
				//$this->set('resellers', $this->Customer->Reseller->generateList());
				$this->set('customer_list',
					$this->Customer->generateList(array("OR"=>array('Customer.customer_id'=>'IS NULL','Customer.customer_id'=>0)),null,null,'{n}.Customer.id','{n}.Customer.company_name'));
			}
		}
	}

	function edit($id = null) {
		if( (isset($this->data['User']['submit'])) || (empty($this->data)) ) {
			if(!$id) {
				$this->Session->setFlash('Invalid User');
				$this->redirect('/');
			}
			$this->data = $this->User->find(array('User.id'=>$id),null,'User.id ASC');
			$this->set('user', $this->data);
		} else {
			if($this->User->save($this->data)) {
				$this->Session->setFlash("This item has been saved. You now need to upload any media for this item");
				$this->redirect("/".strtolower($this->name)."/view/$id");
			} else {
				$this->Session->setFlash('Please correct errors below.');
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

	function search() {
		$query = strtoupper($this->params['form']['q']);
		$this->set('customers', $this->Customer->search($query));		
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
	
	function logout() {
		$this->Session->destroy('User.data');
		$this->redirect($this->referer('/'));
	}

}
?>