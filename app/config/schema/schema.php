<?php 
/* SVN FILE: $Id$ */
/* App schema generated on: 2011-01-18 22:01:08 : 1295389448*/
class AppSchema extends CakeSchema {
	var $name = 'App';
	var $schema = 'schema.php';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $articles = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'length' => 150),
		'slug' => array('type' => 'string', 'null' => false, 'length' => 150),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'section_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'order_by' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10),
		'inline_count' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3),
		'draft' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2),
		'featured' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2),
		'disable_comments' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $customers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'company_name' => array('type' => 'string', 'null' => false, 'length' => 150, 'key' => 'index'),
		'contact_name' => array('type' => 'string', 'null' => false, 'length' => 150),
		'telephone' => array('type' => 'string', 'null' => false, 'length' => 100),
		'email' => array('type' => 'string', 'null' => false, 'length' => 400),
		'fax' => array('type' => 'string', 'null' => false, 'length' => 20),
		'address' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'town' => array('type' => 'string', 'null' => false, 'length' => 50),
		'county' => array('type' => 'string', 'null' => false, 'length' => 50),
		'post_code' => array('type' => 'string', 'null' => false, 'length' => 10),
		'country' => array('type' => 'string', 'null' => false, 'default' => 'UK', 'length' => 50),
		'joined' => array('type' => 'datetime', 'null' => true, 'default' => '0000-00-00 00:00:00'),
		'cancelled' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'customer_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'company_name' => array('column' => array('company_name', 'contact_name', 'telephone', 'fax', 'email', 'address', 'town', 'county', 'post_code'), 'unique' => 0))
	);
	var $groups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 150),
		'slug' => array('type' => 'string', 'null' => false, 'length' => 150),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'group_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $groups_users = array(
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'primary'),
		'indexes' => array('PRIMARY' => array('column' => array('group_id', 'user_id'), 'unique' => 1))
	);
	var $invoices = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'customer_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'service_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'schedule_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '9,4'),
		'next_invoice_due' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'next_invoice_raised' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 1),
		'due_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'date_invoice_paid' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'reference' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 13),
		'your_reference' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 13),
		'vat_included' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'cancelled' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'vat_rate' => array('type' => 'float', 'null' => false, 'default' => '17.50', 'length' => '4,2'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $notes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'customer_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'service_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'website_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'invoice_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'flagged' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'system' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $schedules = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'event_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'customer_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'system_schedule' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $schedules_services = array(
		'schedule_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array()
	);
	var $services = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'length' => 150),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '2', 'length' => 1),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'customer_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'website_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'joined' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'cancelled' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'tmp_pay_schedule' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 150),
		'schedule' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 150),
		'password' => array('type' => 'string', 'null' => false, 'length' => 100),
		'telephone' => array('type' => 'string', 'null' => false, 'length' => 20),
		'email' => array('type' => 'string', 'null' => false, 'length' => 150),
		'address' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'town' => array('type' => 'string', 'null' => false, 'length' => 50),
		'county' => array('type' => 'string', 'null' => false, 'length' => 50),
		'post_code' => array('type' => 'string', 'null' => false, 'length' => 10),
		'country' => array('type' => 'string', 'null' => false, 'length' => 50),
		'joined' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'resigned' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'status' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 1),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $websites = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'uri' => array('type' => 'string', 'null' => false),
		'customer_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'ftp_host' => array('type' => 'string', 'null' => false),
		'ftp_username' => array('type' => 'string', 'null' => false, 'length' => 150),
		'ftp_password' => array('type' => 'string', 'null' => false, 'length' => 150),
		'aliases' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
}
?>
