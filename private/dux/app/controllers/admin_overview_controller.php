<?php
class AdminOverviewController extends AppController
{
	//var $scaffold;
	var $name = 'AdminOverview';
	var $helpers = array('Status','Javascript','Html', 'Form');
	var $uses = array('AdminOverview','Article','Section','Comment','Product','Category');
	
	function index() {
		$category_list = $this->Category->generateList(null,'title ASC',null,'{n}.Category.id','{n}.Category.title');
		if(!empty($category_list)) {
			$category_info = array();
			foreach($category_list as $cat_key => $cat_val)
				$category_info[] = array(
					'title'=>$cat_val,
					'id'=>$cat_key,
					'product_count'=>$this->Product->findCount(array('Category.id'=>$cat_key,'Product.draft'=>0)));
			$this->set('product_list',$this->Product->generateList(null,'created DESC',5,'{n}.Product.id','{n}.Product.title'));
			$this->set('category_info',$category_info);
		}
		$section_list = $this->Section->generateList(null,'title ASC',null,'{n}.Section.id','{n}.Section.title');
		if(!empty($section_list)) {
			$section_info = array();
			foreach($section_list as $sect_key => $sect_val)
				$section_info[] = array(
					'title'=>$sect_val,
					'id'=>$sect_key,
					'article_count'=>$this->Article->findCount(array('Section.id'=>$sect_key,'Article.draft'=>0)));
			$this->set('article_list',$this->Article->generateList(null,'created DESC',5,'{n}.Article.id','{n}.Article.title'));
			$this->set('section_info',$section_info);
		}
		$comment_count['approved'] = $this->Comment->findCount(array('Comment.moderated'=>1,'Comment.spam'=>0));
		$comment_count['moderated'] = $this->Comment->findCount(array('Comment.moderated'=>0,'Comment.spam'=>0));
		$comment_count['spam'] = $this->Comment->findCount(array('Comment.moderated'=>0,'Comment.spam'=>1));
		$this->set('comment_count',$comment_count);
	}
}
?>