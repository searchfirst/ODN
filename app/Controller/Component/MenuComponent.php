<?php
class MenuComponent extends Object {

	var $controller;

	function startup(&$controller) {
		if(empty($controller->plugin)) {
			$this->controller = $controller;
			if($this->controller->actionIsAdmin()) {
				$this->setAdminMenu();
			} else {
				$this->setMenus();
			}
		}
	}

	function setAdminMenu() {
		$admin_menu = array('admin' => 'Dashboard');
		$enabled_modules = Configure::read('Admin.active_modules');
		foreach($enabled_modules as $x=>$module) $admin_menu['admin/'.Inflector::tableize($x)] = Inflector::pluralize($module['alias']);
		$admin_menu['admin/snippets'] = 'Snippets';
		$admin_menu['http://moonlight-project.co.uk/help'] = 'Help';
		$this->controller->set('admin_menu',$admin_menu);
	}

	function setMenus() {
		$this->controller->set('menu_prefix',Configure::read('Menu.prefix'));
		$this->controller->set('menu_suffix',Configure::read('Menu.suffix'));
		$this->controller->set('menu_omissions',Configure::read('Menu.omissions'));
		$this->controller->set('moonlight_website_menu',$this->controller->Section->find('list',array(
			'fields'=>array('Section.slug','Section.title')
		)));
		$this->controller->set('moonlight_product_list',$this->controller->Category->find('list',array(
			'fields'=>array('Category.slug','Category.title'),
			'conditions'=>array('Category.category_id'=>null,'Category.draft'=>false)
		)));
	}
}