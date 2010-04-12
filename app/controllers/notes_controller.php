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
			$service_tmp = array();
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

	function all_notes($page=null) {
		if($page) {
			$all_notes['items'] = $this->Note->find('all',array('limit'=>10,'page'=>$page));
			$all_notes['count'] = $this->Note->find('count');
			$all_notes['curr_page'] = $page;
			$all_notes['pages'] = ceil($all_notes['count']/10);
			$this->set('all_notes',$all_notes);
		} else {
			
		}
	}
	
	function flagged_notes($page=null) {
		if($page) {
			$flagged_notes['items'] = $this->Note->find('all',array('conditions'=>array('Note.flagged'=>1),'limit'=>10,'page'=>$page));
			$flagged_notes['count'] = $this->Note->find('count',array('conditions'=>array('Note.flagged'=>1)));
			$flagged_notes['curr_page'] = $page;
			$flagged_notes['pages'] = ceil($flagged_notes['count']/10);
			$this->set('flagged_notes',$flagged_notes);
		} else {
			
		}
	}
	
	function your_notes($page=null) {
		if($page) {
			$your_notes = $this->Note->findAllCurrentUser(array('limit'=>10,'page'=>$page));
			$this->set('your_notes',$your_notes);
		} else {
			
		}
	}

	function your_flagged_notes($page=null) {
		if($page) {
			$your_flagged_notes = $this->Note->findAllCurrentUser(array('conditions'=>'Note.flagged = 1','limit'=>10,'page'=>$page));
			$this->set('your_flagged_notes',$your_flagged_notes);
		} else {
			
		}
	}
	
	function flag($id=null) {
		global $current_user;
		$cud = $current_user['User']['id'];
		if($id) {
			if(!empty($this->data) && $this->data['Note']['id']==$id) {
				if($this->Note->save($this->data)) {
					$this->redirect($this->referer('/'));
				} else {
					$this->Session->setFlash('There was an error flagging this note');
					$this->redirect($this->referer('/'));
				}
			} else {
				$this->data['Note'] = array('id'=>$id,'flagged'=>1);
			}
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
			return true;
		}
	}

	function unflag($id=null) {
		global $current_user;
		$cud = $current_user['User']['id'];
		if($id) {
			if(!empty($this->data) && $this->data['Note']['id']==$id) {
				if($this->Note->save($this->data)) {
					$this->redirect($this->referer('/'));
				} else {
					$this->Session->setFlash('There was an error unflagging this note');
					$this->redirect($this->referer('/'));
				}
			} else {
				$this->data['Note'] = array('id'=>$id,'flagged'=>0);
			}
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
			return true;
		}
	}

}
?>