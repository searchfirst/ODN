<?php
class WebsitesController extends AppController {
	var $primaryModel = 'Website';
	var $helpers = array('Javascript','Html','Form','Time','TextAssistant','MediaAssistant');
	var $uses = array('Website');
	var $paginate = array(
		'limit' => 10,
		'order' => array('Website.uri' => 'ASC'),
		'recursive' => -1
	);

	function index() {
		$paginationOptions = array();
		$doPaginate = !(isset($this->params['url']['limit']) && $this->params['url']['limit'] == 'all');
		if (!empty($this->params['url']['service_id'])) {
			$paginationOptions['Website.service_id'] = $this->params['url']['service_id'];
		}
		if (!empty($this->params['url']['customer_id'])) {
			$paginationOptions['Website.customer_id'] = $this->params['url']['customer_id'];
		}
		if ($doPaginate) {
			$websites = $this->paginate('Website',$paginationOptions);
		} else {
			$websites = $this->Website->find('all',array(
				'conditions' => $paginationOptions,
				'recursive' => -1
			));
		}
		$this->set('doPaginate',$doPaginate);
		$this->set('websites',$websites);
	}

	function add() {
		if(empty($this->data) || isset($this->data['Referrer']['customer_id'])) {
			$this->data['Website']['customer_id'] =!empty($this->data['Referrer']['customer_id'])?$this->data['Referrer']['customer_id']:null;
			$this->set('website',$this->data);
			$this->set('customer',Set::combine($this->Website->Customer->find('all',array('recursive'=>0)),'{n}.Customer.id','{n}.Customer.company_name'));
//			$this->set('customer', $this->Website->Customer->generateList());
		} else {
			if($this->Website->save($this->data)) {
				$this->Session->setFlash("This item has been saved. You now need to upload any media for this item");
				if(isset($GLOBALS['moonlight_inline_count_set']))
					$this->redirect('/'.strtolower($this->name).'/manageinline/'.$this->Website->getLastInsertId());
				else
					$this->redirect('/'.strtolower($this->name).'/view/'.$this->Website->getLastInsertId());
			} else {
				$this->Session->setFlash('Please correct the errors below');
				$this->data['Referral']['customer_id'] = $this->data['Website']['customer_id'];
				$this->set('customer',Set::combine($this->Website->Customer->find('all',array('recursive'=>0)),'{n}.Customer.id','{n}.Customer.company_name'));
			}
		}
	}

	function edit($id = null) {
		extract($this->Dux->commonRequestInfo());
		if(!$id) {
			$this->cakeError('missingId',array('model'=>'Website'));
		}
		$this->Website->id = $id;
		$this->Website->recursive = -1;

		if (!($isPost || $isPut)) {
			$this->data = $this->Website->read();
		} else {
			if (!isAjax) {
				if($this->Website->save($this->data)) {
					$this->Session->setFlash("Website saved successfully.");
					$this->redirect(array('action'=>'view',$id));
				} else {
					$this->Session->setFlash('Please correct errors below.');
				}
			} else {
				if ($this->Website->save(array('Website' => $this->data['Website']))) {
					$this->set('model',$this->Website->readRoot());
				} else {
					$this->cakeError('ajaxError',array('message'=>'Not saved'));
				}
			}
		}
	}

	function view($id) {
		$this->Website->id = $id;
		if($website = $this->Website->read()) {
			$this->set('website',$website);
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
