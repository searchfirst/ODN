<?php
class FacadesController extends AppController
{
	var $name = 'Facades';
	var $primaryModel = 'Customer';
	var $helpers = array('Status');
	var $layout = 'home';
	var $uses = array('Service','Note');

	function index() {
		$this->set('title_for_layout','Dashboard');
		$current_user = User::getCurrent();
		$cuid = User::getCurrent("id");
		if($current_user) {
			$projects = array(
				'active' => $this->Service->find('customers',array(
					'conditions'=>array('User.id'=>$cuid,'Service.status'=>Service::$status['Active'])
				)),
				'cancelled' => $this->Service->find('customers',array(
					'conditions'=>array('User.id'=>$cuid,'Service.status'=>Service::$status['Cancelled'])
				)),
				'other' => $this->Service->find('customers',array(
					'conditions'=>array('User.id'=>$cuid,'NOT'=>array('Service.status'=>array(Service::$status['Active'],Service::$status['Cancelled'])))
				))
			);
			$this->set('projects',$projects);
			
			$all_notes = array(
				'items'=>$this->Note->find('all',array('order'=>'Note.created DESC','limit'=>10)),
				'count'=>$this->Note->find('count'),
				'curr_page'=>1
			);
			$all_notes['pages'] = ceil($all_notes['count']/10);
			$this->set('all_notes',$all_notes);
			$flagged_notes = array(
				'items'=>$this->Note->find('all',array('conditions'=>array('Note.flagged'=>1),'limit'=>10)),
				'count'=>$this->Note->find('count',array('conditions'=>array('Note.flagged'=>1))),
				'curr_page'=>1
			);
			$flagged_notes['pages'] = ceil($flagged_notes['count']/10);
			$this->set('flagged_notes',$flagged_notes);
			$your_notes = array(
				'items' => $this->Note->find('owned',array('limit'=>10)),
				'count' => $this->Note->find('countOwned'),
				'curr_page' => 1
			);
			$your_notes['pages'] = ceil($your_notes['count']/10);
			$this->set('your_notes',$your_notes);
			$this->set('your_flagged_notes',$this->Note->findForUser($current_user['User']['id'],array('limit'=>10,'conditions'=>'Note.flagged > 0')));
			if ($this->RequestHandler->isAjax()) {
				$this->Dux->renderAjax(array(
					'projects' => $projects,
					'notes' => array(
						'all'=>$all_notes,'flagged'=>$flagged_notes,'your_notes'=>$your_notes
					)
				));
			}
		}
	}
}
?>
