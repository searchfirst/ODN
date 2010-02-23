<?php
class ProtectedSectionsController extends AppController {

	var $name = 'ProtectedSections';
	var $helpers = array('Javascript','Html','Form','Time','TextAssistant','MediaAssistant');

	function index() {
		$this->ProtectedSection->recursive = 0;
		$this->set('protectedSections', $this->ProtectedSection->findAll(null,null,"order_by ASC",null,null,1));
	}

	function view($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Section.');
			$this->redirect('/protected_sections/');
		}
		$this->set('protectedSection', $this->ProtectedSection->read(null, $id));
	}

	function add() {
		if(empty($this->data)) {
			$this->set('resources', $this->ProtectedSection->Resource->generateList());
		} else {
			$this->cleanUpFields();
			if($this->ProtectedSection->save($this->data)) {
				$this->Session->setFlash("This item has been saved. You now need to upload any media for this item");
				if(isset($GLOBALS['moonlight_inline_count_set']))
					$this->redirect('/'.Inflector::underscore($this->name).'/manageinline/'.$this->ProtectedSection->getLastInsertId());
				else
					$this->redirect('/'.Inflector::underscore($this->name).'/view/'.$this->ProtectedSection->getLastInsertId());
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('resources', $this->ProtectedSection->Resource->generateList());
			}
		}
	}

	function edit($id = null) {
		if(isset($this->data['ProtectedSection']['submit']) || empty($this->data)) {
			if(!$id) {
				$this->Session->setFlash('Invalid id for Section');
				$this->redirect('/protected_sections/');
			}
			$this->data = $this->ProtectedSection->read(null, $id);
			$this->set('protectedSection',$this->data);
			$this->set('resources', $this->ProtectedSection->Resource->generateList());
		} else {
			$this->cleanUpFields();
			if($this->ProtectedSection->save($this->data)) {
				$this->Session->setFlash("This item has been saved. You now need to upload any media for this item");
				if(isset($GLOBALS['moonlight_inline_count_set']))
					$this->redirect("/".Inflector::underscore($this->name)."/manageinline/$id");
				else
					$this->redirect("/".Inflector::underscore($this->name)."/view/$id");
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('protectedSection', $this->ProtectedSection->read(null, $id));
				$this->set('resources', $this->ProtectedSection->Resource->generateList());
			}
		}
	}

	function delete($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Section');
			$this->redirect('/protected_sections/');
		}
		if( ($this->data['ProtectedSection']['id']==$id) && ($this->ProtectedSection->del($id)) ) {
			$this->Session->setFlash('The Section deleted: id '.$id.'');
			$this->redirect('/protected_sections/');
		} else {
			$this->set('id',$id);
		}
	}

	function moveup() {
		if(isset($this->data['ProtectedSection']['id']) && isset($this->data['ProtectedSection']['prev_id'])) {
			if($this->ProtectedSection->swapFieldData($this->data['ProtectedSection']['id'],$this->data['ProtectedSection']['prev_id'],'order_by'))
				$this->redirect($this->referer('/protected_sections/'));
			else { 
				$this->Session->setFlash('There was an error swapping the sections');
				$this->redirect($this->referer('/protected_sections/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid sections. Check you selected the correct section');
			$this->redirect($this->referer('/protected_sections/'));
		}
	}
	
	function manageinline($id=null) {
		if($id && ($this->data = $this->ProtectedSection->read(null, $id))) {
			$this->set(Inflector::underscore($this->modelClass),$this->data);
			$this->set('media_data',$this->data['Resource']);
			$db_inline_count = (int) $this->data[$this->modelClass]['inline_count'];
			$actual_inline_count = count($this->data['Resource']);
			if(preg_match('/'.Inflector::underscore($this->name)."\\/(add|edit)/",$this->referer()) && ($db_inline_count == $actual_inline_count))
				$this->redirect('/'.Inflector::underscore($this->name).'/view/'.$id);
			$this->set('inline_data', array('db_count'=>$db_inline_count,'actual_count'=>$actual_inline_count));
			if(!isset($this->data['Resource'])) {
				$this->Session->setFlash('No inline media in '.$this->modelClass);
				$this->redirect('/'.Inflector::underscore($this->name).'/view/'.$id);
			}
		} else {
			$this->Session->setFlash('Invalid '.$this->modelClass.'.');
			$this->redirect('/'.Inflector::underscore($this->name).'/');
		}
	}
}
?>