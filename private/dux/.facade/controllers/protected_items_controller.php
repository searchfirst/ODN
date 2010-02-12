<?php
class ProtectedItemsController extends AppController {

	var $name = 'ProtectedItems';
	
	function index() {
		$this->viewPath = 'errors';
		$this->render('not_found');
	}
	
	function view($slug = null) {
		if(!$slug || (isset($this->params['alt_content']) && $this->params['alt_content']=='Rss')) {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
		$get_article_from_db = $this->Article->find(array('Article.draft'=>0,'Article.slug'=>$slug));
		if(!empty($get_article_from_db)) {
			$this->pageTitle= $get_article_from_db['Article']['title']." – ".$get_article_from_db['Section']['title'];
			$this->set('article', $get_article_from_db);
			$this->set('mod_date_for_layout', $get_article_from_db['Article']['modified']);
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}
}
?>