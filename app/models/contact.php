<?php
class Contact extends AppModel {
	var $name = 'Contact';
	var $displayField = 'name';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasAndBelongsToMany = array(
		'Customer' => array(
			'className' => 'Customer',
			'joinTable' => 'contacts_customers',
			'foreignKey' => 'contact_id',
			'associationForeignKey' => 'customer_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);

}
?>