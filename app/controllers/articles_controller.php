<?php
class ArticlesController extends AppController {

	var $name = 'Articles';
	var $primaryModel = 'Article';
	var $helpers = array('Javascript','Html','Form','Time','TextAssistant','MediaAssistant');
	var $components = array('Rss');

	function index() {
		$this->Article->recursive = 0;
		$this->set('articles', $this->Article->findAll());
	}

	function view($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Article.');
			$this->redirect($this->referer('/sections/'));
		}
		$this->set('article', $this->Article->read(null, $id));
	}

	function add() {
		if(empty($this->data) or isset($this->data['Referrer']['section_id']) ) {
			$this->data['Article']['section_id'] = $this->data['Referrer']['section_id'];
			$this->set('resources', $this->Article->Resource->generateList());
			$this->set('sections', $this->Article->Section->generateList());
		} else {
			$this->cleanUpFields();
			if($this->Article->save($this->data)) {
				$this->Rss->ping();
				$this->Session->setFlash("This item has been saved. You now need to upload any media for this item");
				if(isset($GLOBALS['moonlight_inline_count_set']))
					$this->redirect('/'.strtolower($this->name).'/manageinline/'.$this->Article->getLastInsertId());
				else
					$this->redirect('/'.strtolower($this->name).'/view/'.$this->Article->getLastInsertId());
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('resources', $this->Article->Resource->generateList());
				$this->set('sections', $this->Article->Section->generateList());
			}
		}
	}

	function edit($id = null) {
		if( (isset($this->data['Article']['submit'])) || (empty($this->data)) ) {
			if(!$id) {
				$this->Session->setFlash('Invalid id for Article');
				$this->redirect('/articles/');
			}
			$this->data = $this->Article->read(null, $id);
			$this->set('article', $this->data);
			$this->set('resources', $this->Article->Resource->generateList());
			$this->set('sections', $this->Article->Section->generateList());
		} else {
			$this->cleanUpFields();
			if($this->Article->save($this->data)) {
				$this->Rss->ping();
				$this->Session->setFlash("This item has been saved. You now need to upload any media for this item");
				if(isset($GLOBALS['moonlight_inline_count_set']))
					$this->redirect("/".strtolower($this->name)."/manageinline/$id");
				else
					$this->redirect("/".strtolower($this->name)."/view/$id");
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('article', $this->Article->read(null, $id));
				$this->set('resources', $this->Article->Resource->generateList());
				$this->set('sections', $this->Article->Section->generateList());
			}
		}
	}

	function delete($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Article');
			$this->redirect($this->referer('/articles/'));
		}
		if( ($this->data['Article']['id']==$id) && ($this->Article->del($id)) ) {
			$this->Session->setFlash('Article successfully deleted');
			$this->redirect($this->referer('/articles/'));
		} else {
			$this->set('id',$id);
		}
	}
	
	function moveup() {
		if(isset($this->data['Article']['id']) && isset($this->data['Article']['prev_id'])) {
			if($this->Article->swapFieldData($this->data['Article']['id'],$this->data['Article']['prev_id'],'order_by'))
				$this->redirect($this->referer('/sections/'));
			else { 
				$this->Session->setFlash('There was an error swapping the articles');
				$this->redirect($this->referer('/sections/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid articles. Check you selected the correct article');
			$this->redirect($this->referer('/sections/'));
		}
	}
	
	function manageinline($id=null) {
		if($id && ($this->data = $this->Article->read(null, $id))) {
			$this->set(strtolower($this->modelClass),$this->data);
			$this->set('media_data',$this->data['Resource']);
			$db_inline_count = (int) $this->data[$this->modelClass]['inline_count'];
			$actual_inline_count = count($this->data['Resource']);
			if(preg_match('/'.strtolower($this->name)."\\/(add|edit)/",$this->referer()) && ($db_inline_count == $actual_inline_count))
				$this->redirect('/'.strtolower($this->name).'/view/'.$id);
			$this->set('inline_data', array('db_count'=>$db_inline_count,'actual_count'=>$actual_inline_count));
			if(!isset($this->data['Resource'])) {
				$this->Session->setFlash('No inline media in '.$this->modelClass);
				$this->redirect('/'.strtolower($this->name).'/view/'.$id);
			}
		} else {
			$this->Session->setFlash('Invalid '.$this->modelClass.'.');
			$this->redirect('/'.strtolower($this->name).'/');
		}
	}
}
?>