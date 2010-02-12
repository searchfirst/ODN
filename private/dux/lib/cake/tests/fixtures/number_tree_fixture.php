<?php
/* SVN FILE: $Id: number_tree_fixture.php 5340 2007-06-23 05:56:36Z phpnut $ */
/**
 * Tree behavior class.
 *
 * Enables a model object to act as a node-based tree.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2007, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2007, Cake Software Foundation, Inc.
 * @link				https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package			cake.tests
 * @subpackage		cake.tests.fixtures
 * @since			CakePHP(tm) v 1.2.0.5331
 * @version			$Revision: 5340 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2007-06-23 06:56:36 +0100 (Sat, 23 Jun 2007) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * Number Tree Test Fixture
 *
 * Generates a tree of data for use testing the tree behavior
 *
 * @package		cake
 * @subpackage	cake.tests.fixtures
 */
class NumberTreeFixture extends CakeTestFixture {
	var $name = 'NumberTree';
	var $fields = array ('id' => array (
				'type' => 'integer','key' => 'primary'),
				'name' => array ('type' => 'string','null' => false),
				'parent_id' => 'integer',
					'lft' => array ('type' => 'integer','null' => false),
					'rght' => array ('type' => 'integer','null' => false));
}
?>