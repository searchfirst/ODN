 <?php
class FacadesController extends AppController
{
	var $name = 'Facades';
	var $primaryModel = 'Customer';
	var $helpers = array('Status');
	var $pageTitle = 'Home';
	var $layout = 'home';
	var $uses = array('Service');

	function index() {
		$this->pageTitle = 'Dashboard';
		global $current_user;
		$active_projects = $this->Service->findAll(
			array('Service.user_id'=>$current_user['User']['id'],'Service.status'=>SERVICE_STATUS_ACTIVE),
			null,
			'Customer.company_name',
			null,
			null,
			1
		);
		$cancelled_projects = $this->Service->findAll(
			array('User.id'=>$current_user['User']['id'],'Service.status'=>SERVICE_STATUS_CANCELLED),
			null,
			'Customer.company_name',
			null,
			null,
			1
		);
		$other_projects = $this->Service->findAll(
			array(
				'User.id'=>$current_user['User']['id'],
				'NOT'=>array('Service.status'=>array(SERVICE_STATUS_ACTIVE,SERVICE_STATUS_CANCELLED))
			),
			null,
			'Customer.company_name',
			null,
			null,
			1
		);
		$this->set('active_projects',$active_projects);
		$this->set('cancelled_projects',$cancelled_projects);
		$this->set('other_projects',$other_projects);
	}
}
?>