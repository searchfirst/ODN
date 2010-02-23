<?php
class ResourcesController extends AppController
{
	var $name = 'Resources';
	var $helpers = array('Javascript','Html', 'Form');

/*	function index() {
		$this->Resource->recursive = 0;
		$this->set('resources', $this->Resource->findAll());
	}

	function add() {
		if(empty($this->data)) {
			$this->render();
		} else {
			$this->cleanUpFields();
			if($this->Resource->save($this->data)) {
				$this->Session->setFlash('The Resource has been saved');
				$this->redirect('/resources/index');
			} else {
				$this->Session->setFlash('Please correct errors below.');
			}
		}
	}

	function edit($id) {
		if(empty($this->data)) {
			$this->data = $this->Resource->read(null, $id);
		} else {
			$this->cleanUpFields();
			if($this->Resource->save($this->data)) {
				$this->Session->setFlash('The Resource has been saved');
				$this->redirect('/resources/');
			} else {
				$this->Session->setFlash('Please correct errors below.');
			}
		}
	}*/

	function beforeValidate() {
		$this->sanitiseData();
		return true;
	}
	
	function delete($id) {
		if(!$id) {
			$this->Session->setFlash('Invalid Media');
			$this->redirect($this->referer('/'));
		}
		if(	($this->data['Resource']['id']==$id) &&
			($this->Resource->del($id)) ) {
			$this->Session->setFlash('Media deleted');
			$this->redirect($this->referer('/'));
		} else {
			$this->set('id',$id);
		}
	}

	function moveup() {
		if(isset($this->data['Resource']['id']) && isset($this->data['Resource']['prev_id'])) {
			if($this->Resource->swapFieldData($this->data['Resource']['id'],$this->data['Resource']['prev_id'],'order_by'))
				$this->redirect($this->referer('/'));
			else { 
				$this->Session->setFlash('There was an error swapping the media');
				$this->redirect($this->referer('/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid media. Check you selected the correct media');
			$this->redirect($this->referer('/'));
		}
	}

}
?>