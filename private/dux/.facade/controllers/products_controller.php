<?php
class ProductsController extends AppController
{
	var $name = 'Products';

	function index() {
		if(isset($this->params['alt_content']) && $this->params['alt_content']!='Rss') {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
		$this->set('products',$this->Product->findall("Product.draft=0",null,'Product.modified DESC',20));
		$this->pageTitle = MOONLIGHT_CATEGORIES_TITLE;
	}
	
	function view($slug) {
		$get_product = $this->Product->find(array('Product.draft'=>0,'Product.slug'=>$slug));
		if(!empty($get_product) && (!isset($this->params['alt_content']) || $this->params['alt_content']!='Rss')) {
			$this->pageTitle = $get_product['Product']['title'].': '.$get_product['Category']['title'];
			$this->set('current_parent_section','category-'.$get_product['Category']['slug']);
			$this->set('product', $get_product);
			$this->set('mod_date_for_layout', $get_product['Product']['modified']);
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}
}
?>