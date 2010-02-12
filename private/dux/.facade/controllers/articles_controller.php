<?php
class ArticlesController extends AppController {

	var $name = 'Articles';
	
	function index() {
		if( isset($this->params['alt_content']) && ($this->params['alt_content']=='Rss') &&
		($articles = $this->Article->findAll('Article.draft=0',null,'Article.modified DESC',20)) &&
		(!empty($articles)) ) {
			$this->set('articles',$articles);
		} else {		
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}
	
	function view($slug = null) {
		if(!$slug || (isset($this->params['alt_content']) && $this->params['alt_content']=='Rss')) {
			$this->viewPath = 'errors';
			$this->render('not_found');
			return true;
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