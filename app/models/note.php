<?php
class Note extends AppModel {
	var $name = 'Note';
	var $validate = array();
	var $order = 'Note.modified DESC';
	var $recursive = 1;

	var $hasMany = array();
	var $belongsTo = array('Website','Customer','User','Service');

	function beforeSave() {
		if(empty($this->data['Note']['id']))
			$this->data['Note']['user_id'] = $GLOBALS['current_user']['User']['id'];
		if(!empty($this->data['Note']['service_id']))
			$this->data['Note']['model'] = 'Service';
		return true;
	}
	
	function findAllCurrentUser($options=array()) {

		if(!empty($options['conditions']))
			$conditions = array(" AND {$options['conditions']}"," WHERE {$options['conditions']}");
		else
			$conditions = array("","");
		if(!empty($options['page']))
			$page = $options['page'];
		else
			$page = 1;
		if(!empty($options['limit'])) {
			$t_limit = "LIMIT ".(($page-1)*$options['limit']).", {$options['limit']}";
			$limit = $options['limit'];
		} else {
			$t_limit = '';
			$limit = 0;
		}
		if(!empty($options['order']))
			$order = $options['order'];
		else
			$order = 'Note.created DESC';
		global $current_user;
		$cuid = $current_user['User']['id'];

		$query = <<<EOQ
SELECT * FROM (
(SELECT Note.id FROM customers Customer
	JOIN services Service ON Service.user_id={$cuid} AND Service.customer_id = Customer.id
	JOIN notes Note ON Note.customer_id = Customer.id AND Note.service_id=Service.id
	{$conditions[1]})
UNION
(SELECT Note.id FROM notes Note WHERE Note.user_id={$cuid}{$conditions[0]})
) tmp_tbl
JOIN notes Note ON Note.id = tmp_tbl.id
JOIN services Service ON Service.id = Note.service_id
JOIN customers Customer ON Customer.id = Note.customer_id
JOIN users User ON User.id = Note.user_id
ORDER BY $order
$t_limit
EOQ;

		$count_query = <<<EOQ
SELECT count(id) Count FROM (
(SELECT Note.id FROM customers Customer
	JOIN services Service ON Service.user_id={$cuid} AND Service.customer_id = Customer.id
	JOIN notes Note ON Note.customer_id = Customer.id AND Note.service_id=Service.id
	{$conditions[1]})
UNION
(SELECT Note.id FROM notes Note WHERE Note.user_id={$cuid}{$conditions[0]})
) tmp_tbl
EOQ;
		$q_count = $this->query($count_query);
		$cu_notes['items'] = $this->query($query);
		$cu_notes['count'] = $q_count[0][0]['Count'];
		$cu_notes['pages'] = ($limit)?ceil($cu_notes['count']/$limit):1;
		$cu_notes['curr_page'] = $page;
		return $cu_notes;
	}
}
?>