<?php
class ServicesController extends AppController
{
	var $name = 'Services';
	var $primaryModel = 'Service';
	var $helpers = array('Status','Javascript','Html','Form','Time','TextAssistant','MediaAssistant');
	var $service_titles = array(
		'SEO Plan 1'=>'SEO Plan 1',
		'SEO Plan 2'=>'SEO Plan 2',
		'SEO Plan 3'=>'SEO Plan 3',
		'Link Connections Plan 1'=>'Link Connections Plan 1',
		'Link Connections Plan 2'=>'Link Connections Plan 2',
		'Link Connections Plan 3'=>'Link Connections Plan 3',
		'Web Opto Plan 1'=>'Web Opto Plan 1',
		'Web Opto Plan 2'=>'Web Opto Plan 2',
		'Web Opto Plan 3'=>'Web Opto Plan 3',
		'Web Design'=>'Web Design',
		'Hosting'=>'Hosting',
		'HitMe Tracker'=>'HitMe Tracker'
	);
	var $service_status = array(
		SERVICE_STATUS_PENDING=>'Pending',
		SERVICE_STATUS_ACTIVE=>'Active',
		SERVICE_STATUS_COMPLETE=>'Complete',
		SERVICE_STATUS_CANCELLED=>'Cancelled'
	);
	
	var $service_schedule = array(
		0=>'Monthly',
		1=>'Quarterly',
		2=>'Annual',
		3=>'Manual'
	);

	function index() {
		$this->Service->recursive = 0;
		$this->set('services', $this->Websites->findAll());
	}
	
	function beforeRender() {
		parent::beforeRender();
		$this->set('service_titles',$this->service_titles);
		$this->set('service_status',$this->service_status);
		$this->set('service_schedule',$this->service_schedule);
	}

	function add() {
		if(empty($this->data) || isset($this->data['Referrer']['customer_id'])) {
			$this->data['Service']['customer_id'] =!empty($this->data['Referrer']['customer_id'])?$this->data['Referrer']['customer_id']:null;
			$this->data['Service']['website_id'] =!empty($this->data['Referrer']['website_id'])?$this->data['Referrer']['website_id']:null;
			$this->set('service',$this->data);
			$this->set('customer',
				Set::combine($this->Service->Customer->find('all',array('recursive'=>0)),'{n}.Customer.id','{n}.Customer.company_name'));
			$this->set('website',Set::combine($this->Service->Website->find('all',array(
					'recursive'=>0,
					'conditions'=>array('Website.customer_id'=>$this->data['Service']['customer_id'])
			)),'{n}.Website.id','{n}.Website.uri'));
			$this->set('user',Set::combine($this->Service->User->find('all',array('recursive'=>0)),'{n}.User.id','{n}.User.name'));
		} else {
			if($this->Service->save($this->data)) {
				$this->Session->setFlash("Service added successfully.");
				if(isset($GLOBALS['moonlight_inline_count_set']))
					$this->redirect('/'.strtolower($this->name).'/manageinline/'.$this->Service->getLastInsertId());
				else
					$this->redirect('/'.strtolower($this->name).'/view/'.$this->Service->getLastInsertId());
			} else {
				$this->Session->setFlash('Please correct the errors below');
				$this->data['Referral']['customer_id'] = $this->data['Service']['customer_id'];
				$this->data['Referral']['website_id'] = $this->data['Service']['website_id'];
				$this->set('customer',
					Set::combine($this->Service->Customer->find('all',array('recursive'=>0)),'{n}.Customer.id','{n}.Customer.company_name'));
				$this->set('website',Set::combine($this->Service->Website->find('all',array(
						'recursive'=>0,
						'conditions'=>array('Website.customer_id'=>$this->data['Service']['customer_id'])
				)),'{n}.Website.id','{n}.Website.uri'));
				$this->set('user',Set::combine($this->Service->User->find('all',array('recursive'=>0)),'{n}.User.id','{n}.User.name'));
			}
		}
	}

	function edit($id) {
		if( (isset($this->data['Service']['submit'])) || (empty($this->data)) ) {
			if(!$id) {
				$this->Session->setFlash('Invalid Website');
				$this->redirect('/customers/');
			}
			$this->data = $this->Service->findById($id);
			$this->set('service',$this->data);
			$this->pageTitle = "Edit Service: {$this->data['Service']['title']}";
			$this->set('user',Set::combine($this->Service->User->find('all',array('recursive'=>0)),'{n}.User.id','{n}.User.name'));
			$this->set('customers',
				Set::combine($this->Service->Customer->find('all',array('recursive'=>0)),'{n}.Customer.id','{n}.Customer.company_name'));
		} else {
			$this->set('service',$this->Service->find(array('Service.id'=>$id)));
			if($this->Service->save($this->data)) {
				$this->Session->setFlash("Website saved successfully.");
				$this->redirect("/".strtolower($this->name)."/view/$id");
			} else {
				$this->Session->setFlash('Please correct errors below.');
				pr($this->data);
				//$this->set('service',$this->data);
				$this->set('user',Set::combine($this->Service->User->find('all',array('recursive'=>0)),'{n}.User.id','{n}.User.name'));
				$this->set('customers',
					Set::combine($this->Service->Customer->find('all',array('recursive'=>0)),'{n}.Customer.id','{n}.Customer.company_name'));
				$this->pageTitle = 'Edit Service: '.$this->data['Service']['title'];
			}
		}
	}

	function change_status($id) {
		if($service = $this->Service->find(array('Service.id'=>$id))) {
			$this->set('service',$service);
			if(!empty($this->data)) {
				$active_service_count = $this->Service->findCount(array('Service.customer_id'=>$service['Service']['customer_id'], 'NOT'=>array('OR'=>array('Service.id'=>$service['Service']['id'], 'Service.status'=>SERVICE_STATUS_CANCELLED))));
				$cancel_customer = ($active_service_count==0) && ($this->data['Service']['status']==0);
				$reactivate_customer = ($service['Service']['status']==0) && ($this->data['Service']['status']!=0);
				$this->data['Service']['id'] = $id;
				if($this->data['Service']['status']!=0) $this->data['Service']['cancelled']=null;
				$note['Note'] = $this->data['Note'];
				unset($this->data['Note']);
				if(isset($this->data['Service']['status']) && $this->data['Service']['status']!=$service['Service']['status'] && $this->Service->save($this->data)) {
					if(!empty($note['Note']['description'])) $this->Service->Note->Save($note);
					if($cancel_customer) {
						$customer['Customer'] = array(
							'id' => $service['Service']['customer_id'],
							'cancelled' => strftime('%Y-%m-%d %T'),
						);
						$this->Service->Customer->cancel($customer);
					} else {
						$customer['Customer'] = array(
							'id' => $service['Service']['customer_id'],
							'cancelled' => null,
							'status' => 0
						);
						$this->Service->Customer->save($customer);
					}
					$this->redirect($this->referer('/'));
				} else {
					$this->Session->setFlash('Something went wrong there');
					$this->redirect($this->referer('/'));
				}
			}
		} else {
			$this->viewPath='errors';
			$this->render('bad_reference');
		}
	}

	function view($id) {
		if($service = $this->Service->findById($id)) {		
			$this->set('service', $service);
			$this->pageTitle = "{$service['Service']['title']} Service for {$service['Customer']['company_name']}";
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
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