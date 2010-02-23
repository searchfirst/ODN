<?php
class ProtectedSectionsController extends AppController {

	var $name = 'ProtectedSections';
	var $pageTitle = 'Private Support Area';

	function index() {
		if(!empty($this->data)) {
			if(isset($this->data['ProtectedSection']['logout'])) {
				$this->_deleteSession();
				$this->Session->setFlash('You have successfully logged out');
				$this->redirect('/private');
				return true;
			}
			$username = $this->data['ProtectedSection']['title'];
			$hash = md5($this->data['ProtectedSection']['password']);
			if($protected_section = $this->ProtectedSection->authenticate($username,$hash))
				$this->_saveSession($username,$hash);
		} else {
			$username = $this->Session->read('ProtectedSection.title');
			$hash = $this->Session->read('ProtectedSection.hash');
			$protected_section = $this->ProtectedSection->authenticate($username,$hash);
		}
		if($protected_section) {
			$this->set('protected_section', $protected_section);
		} else {
			$this->render('login');
		}
	}	
	
	function view($slug = null) {
		if(!$slug) {
			$this->Session->setFlash('Invalid id for Section.');
			$this->redirect('/sections/');
		}
		if($slug=='news') $this->Section->bindModel(array('hasMany'=>array('Article'=>array('className'=>'Article','conditions'=>'draft=0','order'=>'modified DESC'))));
		$get_section_from_db = $this->Section->findBySlug($slug);
		if(!empty($get_section_from_db)) {
			$this->pageTitle= $get_section_from_db['Section']['title'];
			$this->set('section', $get_section_from_db);
			$this->set('mod_date_for_layout',
				$this->Section->Article->field('modified',"Article.draft=0 AND Article.section_id={$get_section_from_db['Section']['id']}",'Article.modified DESC'));
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}

	function _saveSession($username,$password_hash) {
		$this->Session->write('ProtectedSection.title',$username);
		$this->Session->write('ProtectedSection.hash',$password_hash);
	}
	
	function _deleteSession() {
		$this->Session->del('ProtectedSection.title');
		$this->Session->del('ProtectedSection.hash');
	}

}
?>