<?php

require_once dirname(__FILE__) . '/bootstrap.php';

/**
 * MentaDemoTest
 * Very simple tests for demonstration purposes.
 *
 * @author Fabrizio Branca
 * @since 2011-11-24
 */
class MentaDemoTest extends PHPUnit_Framework_TestCase {

	public function testDemo() {
		$session = Menta_SessionManager::getSession();
		$session->open('http://www.google.com/ncr');
		$input = $session->element(WebDriver_Container::ID, 'lst-ib');
		$input->value(array('value' => array('Fabrizio Branca')));
		$input->value(array('value' => array(WebDriver_Keys::ReturnKey)));
		Menta_SessionManager::closeSession();
	}

	public function testTitle() {
		$session = Menta_SessionManager::getSession();
		$session->open('http://www.google.com/ncr');
		$assertHelper = Menta_ComponentManager::get('Menta_Component_Helper_Assert'); /* @var $assertHelper Menta_Component_Helper_Assert */
		$assertHelper->setTest($this)->assertTitle('Google');
		Menta_SessionManager::closeSession();
	}

}
