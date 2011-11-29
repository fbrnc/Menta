<?php
/**
 * Wait
 *
 * All wait* methods in this class wait a given time for a condition to be met.
 * If the condition was met before waiting times out true will be return, otherwise false
 *
 * @author Fabrizio Branca
 * @since 2011-11-18
 */
class Menta_Component_Helper_Wait extends Menta_Component_Abstract {

	/**
	 * @var int default timeout
	 */
	protected $defaultTimeout = 30;

	/**
	 * @var int default sleep value;
	 */
	protected $defaultSleep = 1;

	/**
	 * Set default timeout
	 *
	 * @param $defaultTimeout
	 * @return Menta_Component_Helper_Wait
	 */
	public function setDefaultTimeout($defaultTimeout) {
		$this->defaultTimeout = intval($defaultTimeout);
		return $this;
	}

	/**
	 * Set default timeout
	 *
	 * @param $defaultSleep
	 * @return Menta_Component_Helper_Wait
	 */
	public function setDefaultSleep($defaultSleep) {
		$this->defaultSleep = intval($defaultSleep);
		return $this;
	}

	/**
	 * Waiting a given time for a callback to return true
	 *
	 * @static
	 * @param $callback
	 * @param int $timeout
	 * @param int $sleep
	 * @return bool|mixed
	 */
	public function wait($callback, $timeout=null, $sleep=null) {
		$timeout = is_null($timeout) ? $this->defaultTimeout : $timeout;
		$sleep = is_null($sleep) ? $this->defaultSleep : $sleep;
		do {
			$result = call_user_func($callback);
			if ($result) {
				return $result;
			}
			sleep($sleep);
			$timeout -= $sleep;
		} while($timeout > 0);
		return false;
	}

	/**
	 * Wait for element present
	 *
	 * @param string $locator
	 * @param integer $timeout
	 * @param int $sleep
	 * @return bool
	 */
	public function waitForElementPresent($locator, $timeout=null, $sleep=null) {
		$parent = $this;

		return $this->wait(function() use ($locator, $parent) {
			return $parent->getHelperCommon()->isElementPresent($locator); /* @var $parent Menta_Component_Helper_Wait */
		}, $timeout, $sleep);
	}

	/**
	 * Wait for element not present
	 *
	 * @param string|array|WebDriver_Element $locator
	 * @param int $timeout
	 * @param int $sleep
	 * @return bool
	 */
	public function waitForElementNotPresent($locator, $timeout=null, $sleep=null) {
		$parent = $this;

		return $this->wait(function() use ($locator, $parent) {
			return !$parent->getHelperCommon()->isElementPresent($locator); /* @var $parent Menta_Component_Helper_Wait */
		}, $timeout, $sleep);
	}

	/**
	 * Wait for text present
	 *
	 * @param string|array|WebDriver_Element $text
	 * @param int $timeout
	 * @param int $sleep
	 * @return bool
	 */
	public function waitForTextPresent($text, $timeout=null, $sleep=null) {
		$parent = $this;

		return $this->wait(function() use ($text, $parent) {
			return $parent->getHelperCommon()->isTextPresent($text); /* @var $parent Menta_Component_Helper_Wait */
		}, $timeout, $sleep);
	}

	/**
	 * Wait for text not present
	 *
	 * @param string|array|WebDriver_Element $text
	 * @param int $timeout
	 * @param int $sleep
	 * @return bool
	 */
	public function waitForTextNotPresent($text, $timeout=null, $sleep=null) {
		$parent = $this;

		return $this->wait(function() use ($text, $parent) {
			return !$parent->getHelperCommon()->isTextPresent($text); /* @var $parent Menta_Component_Helper_Wait */
		}, $timeout, $sleep);
	}

	/**
	 * Wait for condition (js snippet)
	 *
	 * @param string|array|WebDriver_Element $jsSnippet
	 * @param int $timeout
	 * @param int $sleep
	 * @return bool
	 */
	public function waitForCondition($jsSnippet, $timeout=NULL, $sleep=null) {
		$parent = $this;

		return $this->wait(function() use ($jsSnippet, $parent) {
			return $parent->getHelperCommon()->getEval($jsSnippet); /* @var $parent Menta_Component_Helper_Wait */
		}, $timeout, $sleep);
	}

	/**
	 * Wait for element visible
	 *
	 * @param string $locator
	 * @param integer $timeout
	 * @param int $sleep
	 * @return bool
	 */
	public function waitForElementVisible($locator, $timeout=null, $sleep=null) {
		$parent = $this;

		return $this->wait(function() use ($locator, $parent) {
			return $parent->getHelperCommon()->isVisible($locator); /* @var $parent Menta_Component_Helper_Wait */
		}, $timeout, $sleep);
	}

	/**
	 * Wait for element not visible
	 *
	 * @param string|array|WebDriver_Element $locator
	 * @param int $timeout
	 * @param int $sleep
	 * @return bool
	 */
	public function waitForElementNotVisible($locator, $timeout=null, $sleep=null) {
		$parent = $this;

		return $this->wait(function() use ($locator, $parent) {
			return !$parent->getHelperCommon()->isVisible($locator); /* @var $parent Menta_Component_Helper_Wait */
		}, $timeout, $sleep);
	}

	/**
	 * Wait for element visible
	 *
	 * @deprecated
	 * @param string $locator
	 * @param integer $timeout
	 * @param int $sleep
	 * @return bool
	 */
	public function waitForVisible($locator, $timeout=null, $sleep=null) {
		return $this->waitForElementVisible($locator, $timeout, $sleep);
	}

	/**
	 * Wait for element not visible
	 *
	 * @deprecated
	 * @param string|array|WebDriver_Element $locator
	 * @param int $timeout
	 * @param int $sleep
	 * @return bool
	 */
	public function waitForNotVisible($locator, $timeout=null, $sleep=null) {
		return $this->waitForElementNotVisible($locator, $timeout, $sleep);
	}

	/**
	 * Get common helper
	 * (Needs to be public because it is called inside closures)
	 *
	 * @return Menta_Component_Helper_Common
	 */
	public function getHelperCommon() {
		return Menta_ComponentManager::get('Menta_Component_Helper_Common');
	}

}

