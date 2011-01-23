<?php
class Note extends AppModel {
	var $validate = array();
	var $order = 'Note.modified DESC';
	var $recursive = 1;
	var $actsAs = array('Searchable.Searchable');

	var $hasMany = array();
	var $belongsTo = array('Website','Customer','User','Service');

	function beforeSave() {
		if(empty($this->data['Note']['id']))
			$this->data['Note']['user_id'] = User::getCurrent('id');
		if(!empty($this->data['Note']['service_id']))
			$this->data['Note']['model'] = 'Service';
		return true;
	}
	
	function findForUser($cuid,$options=array()) {

		if(!empty($options['conditions']))
			$conditions = " AND ".$options['conditions'];
		else
			$conditions = "";
		if(!empty($options['page']))
			$page = $options['page'];
		else
			$page = 1;
		if(!empty($options['limit'])) {
			$t_limit = " LIMIT ".(($page-1)*$options['limit']).", {$options['limit']}";
			$limit = $options['limit'];
		} else {
			$t_limit = '';
			$limit = 0;
		}
		if(!empty($options['order']))
			$order = $options['order'];
		else
			$order = 'Note.created DESC';

		$query = sprintf("SELECT * FROM users User JOIN customers Customer JOIN services Service JOIN notes Note ON Service.customer_id=Customer.id AND Note.service_id=Service.id AND User.id=Note.user_id WHERE (Note.user_id=%s OR Service.user_id=%s)%s ORDER BY %s%s",$cuid,$cuid,$conditions,$order,$t_limit);

		$count_query = sprintf("SELECT count(Note.id) Count FROM customers Customer JOIN services Service JOIN notes Note ON Service.customer_id=Customer.id AND Note.service_id=Service.id WHERE (Note.user_id=%s OR Service.user_id=%s)%s",$cuid,$cuid,$conditions);

		$q_count = $this->query($count_query);
		$this->log($q_count);
		$cu_notes['items'] = $this->query($query);
		$cu_notes['count'] = $q_count[0][0]['Count'];
		$cu_notes['pages'] = ($limit)?ceil($cu_notes['count']/$limit):1;
		$cu_notes['curr_page'] = $page;
		return $cu_notes;
	}
}
?>
