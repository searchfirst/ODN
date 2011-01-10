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
//		$this->Service->recursive = 0;
//		$this->set('services', $this->Services->find('all'));
//		$this->Session->setFlash('');
		$this->redirect('/');
	}
	
	function beforeRender() {
		parent::beforeRender();
		$this->set('service_titles',$this->service_titles);
		$this->set('service_status',$this->service_status);
		$this->set('service_schedule',$this->service_schedule);
	}

	function add() {
		if(empty($this->data) || isset($this->data['Referrer']['customer_id'])) {
			if(!empty($this->data['Referrer']['customer_id']))
				$this->data['Service']['customer_id'] = $this->data['Referrer']['customer_id'];
			if(!empty($this->data['Referrer']['website_id']))
				$this->data['Service']['website_id'] = $this->data['Referrer']['website_id'];
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
				$customer_id = $this->data['Service']['customer_id'];
				$current_customer_status = $this->Service->Customer->field('Customer.status',array('Customer.id'=>$customer_id));
				if($current_customer_status!=0) {
					$update_customer_data = array('Customer'=>array('id'=>$customer_id,'status'=>0));
					$this->Service->Customer->save($update_customer_data);
				}
				if($this->RequestHandler->isAjax())
					$this->redirect($this->referer('/'));
				else {
					$this->redirect("/customers/view/$customer_id");
				}
			} else {
				$this->Session->setFlash('Please correct the errors below');
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
//		if( (isset($this->data['Service']['submit'])) || (empty($this->data)) ) {
		if( empty($this->data) ) {
			if(!$id) {
				$this->Session->setFlash('Invalid Service');
				$this->redirect($this->referer('/'));
			}
			$this->data = $this->Service->findById($id);
			//$this->set('service',$this->data);
			$this->pageTitle = "Edit Service: {$this->data['Service']['title']}";
			$this->set('user',Set::combine($this->Service->User->find('all',array('recursive'=>0)),'{n}.User.id','{n}.User.name'));
			$this->set('customers',
				Set::combine($this->Service->Customer->find('all',array('recursive'=>0)),'{n}.Customer.id','{n}.Customer.company_name'));
		} else {
			//$this->set('service',$this->Service->find(array('Service.id'=>$id)));
			if($this->Service->save($this->data)) {
				$this->Session->setFlash("Website saved successfully.");
				$this->redirect($this->referer("/customers/view/{$this->data['Service']['customer_id']}"));
			} else {
				$this->Session->setFlash('Please correct errors below.');
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

	function activity_monitor($filter=null,$date_range=null) {
		if($filter!=null) {
			if(!$date_range) {
				$date_range = array(
					'start' => strftime('%F %T',strtotime('6 months ago')),
					'end' => strftime('%F %T',strtotime('tomorrow'))
				);
			} else {
				$date_range_split = 'deal with this';
			}
			$dates = array();
			$start_date_ts = strtotime(sprintf('%s01',substr($date_range['start'],0,8)));
			$end_date_ts = strtotime(sprintf('%s01',substr($date_range['end'],0,8)));
			$curr_date_ts = $start_date_ts;
			while($curr_date_ts != $end_date_ts) {
				$formatted_date = strftime('%b %Y',$curr_date_ts);
				$dates[] = $formatted_date;
				$curr_date_ts = strtotime('+1 month',$curr_date_ts);
			}
			if(false===($customers=Cache::read("activity_monitor_customerlist_$filter"))) {
				$customers = $this->Service->find('all',array(
					'conditions'=>array(
						'Service.title LIKE ' => "%$filter%",
						'NOT' => array('Service.status' => Service::$status['Cancelled'])
					),
					'fields'=>array(
						'Customer.company_name',
						'Customer.id',
						"CONCAT(Customer.company_name,': ',Service.title) AS company_service_hash"
					),
					'order'=>'company_service_hash'
				));
				Cache::write("activity_monitor_customerlist_$filter", $customers);
			}
			if(false===($services=Cache::read("activity_monitor_notelist_$filter"))) {
				$services = $this->Service->Note->find('all',array(
					'conditions' => array(
						'Service.title LIKE ' => "%$filter%",
						'Note.created BETWEEN ? AND ?' => array($date_range['start'],$date_range['end']),
						'NOT' => array('Service.status' => Service::$status['Cancelled'])
					),
					'fields' => array(
						'Note.id',
						'Note.description',
						'User.name',
						"DATE_FORMAT(Note.created,'%b %Y') AS month_created",
						'Service.id',
						'Service.title',
						'Customer.company_name',
						'Customer.id',
						"CONCAT(Customer.company_name,': ',Service.title) AS company_service_hash"
					),
					'group'=>'Note.created',
					'order'=>'Note.created ASC',
					'recursive' => 0
				));
				Cache::write("activity_monitor_notelist_$filter", $services);
			}
			$customer_date_table = array();
			foreach($services as $service) {
				$csh = $service[0]['company_service_hash'];
				$month = $service[0]['month_created'];
				unset($service[0]);
				$customer_date_table[$csh][$month][] = $service;
			}
			$this->set('dates',$dates);
			$this->set('customers',$customers);
			$this->set('customer_date_table',$customer_date_table);
		} 
	}

}
?>