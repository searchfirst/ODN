<?php
class SectionsController extends AppController {

	var $name = 'Sections';
	var $helpers = array('Javascript','Html','Form','Time','TextAssistant','MediaAssistant');

	function index() {
		$this->Section->recursive = 0;
		$this->set('sections', $this->Section->findAll(null,null,"order_by ASC",null,null,1));
	}

	function view($id=null,$page=1) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Section.');
			$this->redirect('/sections/');
		} else {
			$slug = $this->Section->field('slug',array("Section.id"=>$id));
			if(preg_match('/(events|news|blog|log)/i',$slug)) {
				$this->Section->bindModel(array('hasMany'=>array(
					'Article'=>array('className'=>'Article','conditions'=>'draft=0','order'=>'modified ASC'))));
				if($section_data = $this->Section->read(null,$id)) {
					$section_data_articles = $this->Section->Article->findAll(array("Section.id"=>$id),null,"Article.created DESC",10,$page,1);
					//Loop through results and add ['Article'] contents to array
					$section_data['Article'] = array();
					foreach($section_data_articles as $section_data_article)
						$section_data['Article'][] = $section_data_article['Article'];
					$page_data['num_pages'] = ceil($this->Section->Article->findCount(array("Section.id"=>$id))/10);
					$page_data['has_prev'] = ($page > 1);
					$page_data['has_next'] = ($page < $page_data['num_pages']);
					$page_data['current'] = $page;
					$this->set('page_data',$page_data);
					$this->set('section',$section_data);
				} else {
					$this->viewPath = 'errors';
					$this->render('not_found');
				}
			} else {
				$this->set('section', $this->Section->read(null, $id));
			}
		}
	}

	function add() {
		if(empty($this->data)) {
			$this->set('resources', $this->Section->Resource->generateList());
		} else {
			$this->cleanUpFields();
			if($this->Section->save($this->data)) {
				$this->Session->setFlash("This item has been saved. You now need to upload any media for this item");
				if(isset($GLOBALS['moonlight_inline_count_set']))
					$this->redirect('/'.strtolower($this->name).'/manageinline/'.$this->Section->getLastInsertId());
				else
					$this->redirect('/'.strtolower($this->name).'/view/'.$this->Section->getLastInsertId());
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('resources', $this->Section->Resource->generateList());
			}
		}
	}

	function edit($id = null) {
		if(isset($this->data['Section']['submit']) || empty($this->data)) {
			if(!$id) {
				$this->Session->setFlash('Invalid id for Section');
				$this->redirect('/sections/');
			}
			$this->data = $this->Section->read(null, $id);
			$this->set('section',$this->data);
			$this->set('resources', $this->Section->Resource->generateList());
		} else {
			$this->cleanUpFields();
			if($this->Section->save($this->data)) {
				$this->Session->setFlash("This item has been saved. You now need to upload any media for this item");
				if(isset($GLOBALS['moonlight_inline_count_set']))
					$this->redirect("/".strtolower($this->name)."/manageinline/$id");
				else
					$this->redirect("/".strtolower($this->name)."/view/$id");
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('section', $this->Section->read(null, $id));
				$this->set('resources', $this->Section->Resource->generateList());
			}
		}
	}

	function delete($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Section');
			$this->redirect('/sections/');
		}
		if( ($this->data['Section']['id']==$id) && ($this->Section->del($id)) ) {
			$this->Session->setFlash('The Section deleted: id '.$id.'');
			$this->redirect('/sections/');
		} else {
			$this->set('id',$id);
		}
	}

	function moveup() {
		if(isset($this->data['Section']['id']) && isset($this->data['Section']['prev_id'])) {
			if($this->Section->swapFieldData($this->data['Section']['id'],$this->data['Section']['prev_id'],'order_by'))
				$this->redirect($this->referer('/sections/'));
			else { 
				$this->Session->setFlash('There was an error swapping the sections');
				$this->redirect($this->referer('/sections/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid sections. Check you selected the correct section');
			$this->redirect($this->referer('/sections/'));
		}
	}
	
	function manageinline($id=null) {
		if($id && ($this->data = $this->Section->read(null, $id))) {
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