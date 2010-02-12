<?php
class SectionsController extends AppController {

	var $name = 'Sections';

	function view($slug = null) {
		if(!$slug) {
			$this->Session->setFlash('Invalid id for Section.');
			$this->redirect('/');
		}
		if($slug=='news')
			$this->Section->bindModel(array('hasMany'=>array('Article'=>array('className'=>'Article','conditions'=>'draft=0','order'=>'modified DESC'))));
		if($slug=='blog')
			$this->Section->bindModel(array('hasMany'=>array('Article'=>array('className'=>'Article','conditions'=>'draft=0','order'=>'created DESC','limit'=>'15'))));
		$get_section_from_db = $this->Section->findBySlug($slug);
		if(!empty($get_section_from_db)) {
			$this->pageTitle = $get_section_from_db['Section']['title'];
			$this->set('section', $get_section_from_db);
			$this->set('mod_date_for_layout',
				$this->Section->Article->field('modified',array('Article.draft'=>0,'Article.section_id'=>$get_section_from_db['Section']['id']),'Article.modified DESC'));
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}

	function archive($slug=null,$year=null,$month=null,$day=null,$legacy=null) {
		if(!$slug) {
			$this->viewPath = 'errors';
			$this->render('not_found');
			return true;
		}
		
		if($day && $legacy) {
			if(!is_numeric($legacy)) {
				$this->viewPath = 'errors';
				$this->render('not_found');
				return true;
			}
			$legacy_article = $this->Section->Article->field('slug',array("Article.created"=>gmstrftime("%y:%m:%d %T",$legacy)));
			if(!empty($legacy_article)) {
				header('Location: http://'.$_SERVER['HTTP_HOST']."/articles/$legacy_article",true,'301');
				return true;
			} else {
				$this->redirect("/archive/");
				return true;
			}
		}

		$section_id = $this->Section->field('id',array('Section.slug'=>$slug));
		$section_data = $this->Section->find(array('Section.slug'=>$slug),null,null,1);
		$this->pageTitle = $section_data['Section']['title']." Archive";
		$months = array('January','February','March','April','May','June','July','August','September','October','November','December');
		if($year && $month) {
			$year = ucwords($year);
			$month = ucwords($month);
			$this_date = date('Y-m-d 00:00:00',strtotime("$month $year"));
			$next_date = date('Y-m-d 00:00:00',strtotime('+1 month',strtotime($this_date)));
			$archive_array[$year][$month] = $this->Section->Article->findAll(
				array('Section.slug'=>$slug,'and'=>array('Article.created'=>"BETWEEN $this_date AND $next_date")),
				null,'Article.created DESC',null,0,1);
			$this->set('archive_array',$archive_array);
		} elseif($year && !$month) {
			foreach($months as $month) {			
				$this_date = date('Y-m-d 00:00:00',strtotime("$month $year"));
				$next_date = date('Y-m-d 00:00:00',strtotime('+1 month',strtotime($this_date)));
				$archive_array[$year][$month] = $this->Section->Article->findAll(
					array('Section.slug'=>$slug,'and'=>array('Article.created'=>"BETWEEN $this_date AND $next_date")),
					null,'Article.created DESC',null,0,1);
				$this->set('archive_array',$archive_array);
			}		
		} else {
			$query = $this->Section->Article->query("SELECT YEAR(MAX(created)) AS max_year,YEAR(MIN(created)) AS min_year from {$this->Section->Article->table}");
			$m = $query[0][0];
			for($y=((int) $m['min_year']);$y<=((int) $m['max_year']);$y++) {
				$year = $y;
				foreach($months as $month) {
					$this_date = date('Y-m-d 00:00:00',strtotime("$month $year"));
					$next_date = date('Y-m-d 00:00:00',strtotime('+1 month',strtotime($this_date)));
					$archive_array[$year][$month] = $this->Section->Article->findAll(
						array('Section.slug'=>$slug,'and'=>array('Article.created'=>"BETWEEN $this_date AND $next_date")),
						null,'Article.created DESC',null,0,1);					
				}
			}
			$this->set('archive_array',$archive_array);
		}
	}

}
?>