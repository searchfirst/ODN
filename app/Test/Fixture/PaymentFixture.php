<?php
/* Payment Fixture generated on: 2011-09-29 15:40:39 : 1317307239 */
class PaymentFixture extends CakeTestFixture {
	var $name = 'Payment';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
		'amount' => array('type' => 'float', 'null' => true),
		'reference' => array('type' => 'string', 'null' => true, 'length' => 128),
		'payment_date' => array('type' => 'date', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'indexes' => array('PRIMARY' => array('unique' => true, 'column' => 'id')),
		'tableParameters' => array()
	);

	var $records = array(
		array(
			'id' => 1,
			'amount' => 1,
			'reference' => 'Lorem ipsum dolor sit amet',
			'payment_date' => '2011-09-29',
			'modified' => '2011-09-29 15:40:39',
			'created' => '2011-09-29 15:40:39'
		),
	);
}
