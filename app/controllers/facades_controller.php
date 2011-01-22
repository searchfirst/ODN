<?php
class FacadesController extends AppController
{
	var $name = 'Facades';
	var $primaryModel = 'Customer';
	var $helpers = array('Status');
	var $pageTitle = 'Home';
	var $layout = 'home';
	var $uses = array('Service','Note');

	function index() {
		$this->set('title_for_layout','Dashboard');
		$current_user = $this->User->getCurrent();
		if($current_user) {
			$active_projects = $this->Service->find('all',array(
				'conditions'=>array('User.id'=>$current_user['User']['id'],'Service.status'=>SERVICE_STATUS_ACTIVE),
				'order'=>'Customer.company_name ASC',
				'recursive'=>1,
			));
			$cancelled_projects = $this->Service->find('all',array(
				'conditions'=>array('User.id'=>$current_user['User']['id'],'Service.status'=>SERVICE_STATUS_CANCELLED),
				'order'=>'Customer.company_name ASC',
				'recursive'=>1,
			));
			$other_projects = $this->Service->find('all',array(
				'conditions'=>array('User.id'=>$current_user['User']['id'],'NOT'=>array('Service.status'=>array(SERVICE_STATUS_ACTIVE,SERVICE_STATUS_CANCELLED))),
				'order'=>'Customer.company_name ASC',
				'recursive'=>1,
			));
			$this->set('active_projects',$active_projects);
			$this->set('cancelled_projects',$cancelled_projects);
			$this->set('other_projects',$other_projects);
			$all_notes = array(
				'items'=>$this->Note->find('all',array('order'=>'Note.created DESC','limit'=>10)),
				'count'=>$this->Note->find('count',array('order'=>'Note.created DESC')),
				'curr_page'=>1
			);
			$all_notes['pages'] = ceil($all_notes['count']/10);
			$this->set('all_notes',$all_notes);
			$flagged_notes = array(
				'items'=>$this->Note->find('all',array('conditions'=>array('Note.flagged'=>1),'limit'=>10)),
				'count'=>$this->Note->find('count',array('conditions'=>array('Note.flagged'=>1),'limit'=>10)),
				'curr_page'=>1
			);
			$flagged_notes['pages'] = ceil($flagged_notes['count']/10);
			$this->set('flagged_notes',$flagged_notes);
			$this->set('your_notes',$this->Note->findForUser($current_user['User']['id'],array('limit'=>10)));
			$this->set('your_flagged_notes',$this->Note->findForUser($current_user['User']['id'],array('limit'=>10,'conditions'=>'Note.flagged > 0')));
		}
	}
}
?>
