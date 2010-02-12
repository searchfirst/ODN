<?php
class CategoriesController extends AppController {

	var $name = 'Categories';

	function beforeRender() {
		AppController::beforeRender();
		if(!empty($this->viewVars['category']['Category']))
			$this->set('subcategory_list', $this->Category->findAll("Category.category_id={$this->viewVars['category']['Category']['id']}"));
	}
	
	function index() {
		if(isset($this->params['alt_content']) && $this->params['alt_content']=='Rss') {
			$this->viewPath = 'errors';
			$this->render('not_found');}
		$this->pageTitle = MOONLIGHT_CATEGORIES_TITLE;
		$this->set('categories',$this->Category->findAll());
	}
	
	function view($slug = null) {
		if(!$slug) {
			$this->Session->setFlash('Invalid id for Category.');
			$this->render('error');}
		$get_category_from_db = $this->Category->findBySlug($slug);
		if(!empty($get_category_from_db)) {
			$this->pageTitle = $get_category_from_db['Category']['title'];
			$this->set('current_parent_section','category-'.$get_category_from_db['Category']['slug']);
			$this->set('category', $get_category_from_db);
			$this->set('mod_date_for_layout', $this->Category->Product->field('modified',"Product.draft=0 AND Product.category_id={$get_category_from_db['Category']['id']}",'Product.modified DESC'));
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}

}
?>