<?php
class NotesController extends AppController
{
	var $name = 'Notes';
	var $primaryModel = 'Note';
	var $helpers = array('Status','Javascript','Html','Form','Time','TextAssistant','MediaAssistant');

	function beforeFilter() {
		if(!isset($this->params['alt_content']) || $this->params['alt_content']!='Rss')
			parent::beforeFilter();
	}

	function index() {
		$notes = $this->Note->findAll(array('Note.created >= DATE_SUB(CURDATE(),INTERVAL 7 DAY)'),null,'Note.created DESC',null,null,0);
		$this->set('notes',$notes);
	}
	
	function beforeRender() {
		parent::beforeRender();
		return true;
	}

	function add() {
		if(isset($this->params['url']['customer_id'])) $this->data['Referrer']['customer_id'] = $this->params['url']['customer_id'];
		if(empty($this->data) || isset($this->data['Referrer']['customer_id'])) {
			$service_list = $this->Note->Customer->Service->findAll(
				array('Customer.id'=>$this->data['Referrer']['customer_id']),
				null,
				'Service.cancelled'
			);
			foreach($service_list as $service_item) {
				$service_tmp[$service_item['Service']['id']] = (($service_item['Service']['status']=='0')?'[Cancelled] ':'').$service_item['Website']['uri'].' '.$service_item['Service']['title'];
			}
			$this->set('services',$service_tmp);
			$this->data['Note']['customer_id'] = $this->data['Referrer']['customer_id'];
		} else {
			if($this->Note->save($this->data)) {
				$this->Session->setFlash("Note added.");
				$this->redirect($this->referer('/'));
			} else {
				$this->Session->setFlash('Please correct the errors below');
				$this->data['Referrer']['customer_id'] = $this->data['Note']['customer_id'];
				$this->data['Referrer']['service_id'] = $this->data['Note']['service_id'];
				$service_list = $this->Note->Customer->Service->findAll(
					array('Customer.id'=>$this->data['Referrer']['customer_id']),
					null,
					'Service.cancelled'
				);
				$service_tmp = array();
				foreach($service_list as $service_item) {
					$service_tmp[$service_item['Service']['id']] = (($service_item['Service']['status']=='0')?'[Cancelled] ':'').$service_item['Website']['uri'].' '.$service_item['Service']['title'];
				}
				$this->set('services',$service_tmp);
			}
		}
	}

}
?>