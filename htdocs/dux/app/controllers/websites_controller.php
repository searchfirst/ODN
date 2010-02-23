<?php
class WebsitesController extends AppController
{
	var $name = 'Websites';
	var $helpers = array('Javascript','Html','Form','Time','TextAssistant','MediaAssistant');

	function beforeFilter() {
		parent::beforeFilter();
		$this->permissions = array(
			"index"=>array(
				'owner'=>null,
				'admin'=>array('group'=>array('Admin'),'conditions'=>array()),
				'group'=>array('group'=>array('User'),'conditions'=>array('Customer.customer_id'=>0)),
				'other'=>array('group'=>array(),'conditions'=>null)
			),
			"view"=>array(
				'owner'=>array('owner_conditions'=>array('OR'=>array('Customer.user_id'=>$this->current_user['User']['id'])),'conditions'=>array()),
				'admin'=>array('group'=>array('Admin'),'conditions'=>array()),
				'group'=>array('group'=>array(),'conditions'),
				'other'=>array('group'=>array(),'conditions')
			),
			"edit"=>array(
				'owner'=>array('owner_conditions','conditions'),
				'admin'=>array('group'=>array('Admin'),'conditions'),
				'group'=>array('group','conditions'),
				'other'=>array('group','conditions')
			),
			"add"=>array(
				'owner'=>array('owner_conditions','conditions'),
				'admin'=>array('group'=>array('Admin'),'conditions'),
				'group'=>array('group','conditions'),
				'other'=>array('group','conditions')
			),
			"delete"=>array(
				'owner'=>array('owner_conditions','conditions'),
				'admin'=>array('group'=>array('Admin'),'conditions'),
				'group'=>array('group','conditions'),
				'other'=>null
			)
		);
		
	}

	function index() {
		$this->Website->recursive = 0;
		$this->set('websites', $this->Websites->findAll());
	}

	function add() {
		if(empty($this->data) || isset($this->data['Referrer']['customer_id'])) {
			$this->data['Website']['customer_id'] =!empty($this->data['Referrer']['customer_id'])?$this->data['Referrer']['customer_id']:null;
			$this->set('website',$this->data);
			$this->set('customer', $this->Website->Customer->generateList());
		} else {
			$this->cleanUpFields();
			if($this->Website->save($this->data)) {
				$this->Session->setFlash("This item has been saved. You now need to upload any media for this item");
				if(isset($GLOBALS['moonlight_inline_count_set']))
					$this->redirect('/'.strtolower($this->name).'/manageinline/'.$this->Website->getLastInsertId());
				else
					$this->redirect('/'.strtolower($this->name).'/view/'.$this->Website->getLastInsertId());
			} else {
				$this->Session->setFlash('Please correct the errors below');
				$this->data['Referral']['customer_id'] = $this->data['Website']['customer_id'];
				$this->set('customer', $this->Website->Customer->generateList());
			}
		}
	}

	function edit($id) {
		if( (isset($this->data['Website']['submit'])) || (empty($this->data)) ) {
			if(!$id) {
				$this->Session->setFlash('Invalid Website');
				$this->redirect('/customers/');
			}
			$this->data = $this->Website->read(null, $id);
			$this->set('website',$this->data);
			$this->set('customers', $this->Website->Customer->generateList());
		} else {
			$this->cleanUpFields();
			if($this->Website->save($this->data)) {
				$this->Session->setFlash("Website saved successfully.");
				$this->redirect("/".strtolower($this->name)."/view/$id");
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('product',$this->data);
				$this->set('customers', $this->Website->Customer->generateList());
			}
		}
	}

	function view($id) {
		$conditions = am(array('Website.id'=>$id),$this->generateConditions($this->Website,null,null,null,'findWithService'));
		if($this->permissionsStatus['admin'] || $this->permissionsStatus['owner']) {
			if($website = $this->Website->findWithService($conditions))
				$this->set('website',$website);
			else {
				$this->viewPath = 'errors';
				$this->render('not_found');
				return true;
			}
		} else {
			$this->viewPath = 'errors';
			$this->render('not_authorised');
			return true;			
		}
	}

	function delete($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Product');
			$this->redirect($this->referer('/customers/'));
		}
		if( ($this->data['Website']['id']==$id) && ($this->Website->del($id)) ) {
			$this->Session->setFlash('Product deleted: id '.$id.'.');
			$customer_id = $this->Website->Customer->findByProduct($id);
			$this->redirect($this->referer('/customers/'));
		} else {
			$this->set('id',$id);
		}
	}
}
?>