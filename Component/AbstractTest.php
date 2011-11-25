<?php
/**
 * Abstract component class for components that need access to the current PHPUnit testcase
 *
 * @author Fabrizio Branca
 * @since 2011-11-24
 */
abstract class Menta_Component_AbstractTest extends Menta_Component_Abstract {

	/**
	 * @var PHPUnit_Framework_TestCase
	 */
	protected $test;

	/**
	 * Set test object
	 *
	 * @param PHPUnit_Framework_TestCase $test
	 * @return Menta_Component_Helper_Assert
	 */
	public function setTest(PHPUnit_Framework_TestCase $test) {
		$this->test = $test;
		return $this;
	}

	/**
	 * Get test object
	 *
	 * @return PHPUnit_Framework_TestCase
	 */
	public function getTest() {
		if (is_null($this->test)) {
			throw new Exception('No testcase object available');
		}
		return $this->test;
	}
	
}
