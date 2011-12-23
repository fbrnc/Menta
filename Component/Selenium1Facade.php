<?php

/**
 * Facade for methods that were available in PHPUnit_Extensions_SeleniumTestCase.
 * This component is intendet to be used from the magic __call() method of the
 * Menta_Testcase_Selenium1, which aims to be a drop-in-replacement for
 * PHPUnit_Extensions_SeleniumTestCase.
 *
 * This also can be used by developers that are familiar with the Selenium 1 api implemented
 * in PHPUnit_Extensions_SeleniumTestCase, not wanting to switch to Selenium 2
 *
 * @throws Exception
 */
class Menta_Component_Selenium1Facade extends Menta_Component_AbstractTest {

	/**
	 * @var int default timeout
	 */
	protected $timeout = 60;

	/**
	 * @var string base url (was needed in Selenium 1 and wil be used here as a prefix for all requests)
	 */
	protected $browserUrl;

	public function setBrowserUrl($browserUrl) {
		$this->browserUrl = $browserUrl;
	}

	/**
	 * Assert title
	 *
	 * @param string $title
	 * @return void
	 */
	public function assertTitle($title) {
		$this->getHelperAssert()->assertTitle($title);
	}

	/**
	 * Check if element is present
	 *
	 * @param string|array|WebDriver_Element $element
	 * @return bool
	 */
	public function isElementPresent($element) {
		return $this->getHelperCommon()->isElementPresent($element);
	}

	/**
	 * Check if text is present
	 *
	 * @param string $text
	 * @return bool
	 */
	public function isTextPresent($text) {
		return $this->getHelperCommon()->isTextPresent($text);
	}

	/**
	 * Wait for element present
	 *
	 * @param string|array|WebDriver_Element $locator
	 * @param int $timeout
	 * @param string $message
	 * @return void
	 */
	public function waitForElementPresent($locator, $timeout=null, $message=null) {
		if (is_null($message)) {
			$message = sprintf("Waiting for element '%s' timed out", $this->getHelperCommon()->element2String($locator));
			if (!is_null($timeout)) {
				$message .= " after $timeout seconds";
			}
		}
		if (!$this->getHelperWait()->waitForElementPresent($locator, $timeout)) {
			$this->getTest()->fail($message);
		}
	}

	/**
	 * Wait for element not present
	 *
	 * @param string|array|WebDriver_Element $locator
	 * @param int $timeout
	 * @param string $message
	 * @return void
	 */
	public function waitForElementNotPresent($locator, $timeout=null, $message=null) {
		if (is_null($message)) {
			$message = sprintf("Waiting for element '%s' disappearing timed out", $this->getHelperCommon()->element2String($locator));
			if (!is_null($timeout)) {
				$message .= " after $timeout seconds";
			}
		}
		if (!$this->getHelperWait()->waitForElementNotPresent($locator, $timeout)) {
			$this->getTest()->fail($message);
		}
	}

	/**
	 * Wait for text present
	 *
	 * @param string $text
	 * @param int $timeout
	 * @param string $message
	 * @return void
	 */
	public function waitForTextPresent($text, $timeout=null, $message=null) {
		if (is_null($message)) {
			$message = sprintf("Waiting for text '%s' timed out", $text);
			if (!is_null($timeout)) {
				$message .= " after $timeout seconds";
			}
		}
		if (!$this->getHelperWait()->waitForTextPresent($text, $timeout)) {
			$this->getTest()->fail($message);
		}
	}

	/**
	 * Wait for text not present
	 *
	 * @param string $text
	 * @param int $timeout
	 * @param string $message
	 * @return void
	 */
	public function waitForTextNotPresent($text, $timeout=null, $message=NULL) {
		if (is_null($message)) {
			$message = sprintf("Waiting for text '%s' disappearing timed out", $text);
			if (!is_null($timeout)) {
				$message .= " after $timeout seconds";
			}
		}
		if (!$this->getHelperWait()->waitForTextNotPresent($text, $timeout)) {
			$this->getTest()->fail($message);
		}
	}

	/**
	 * Wait for condition
	 *
	 * @param string $jsSnippet
	 * @param int $timeout
	 * @param string $message
	 * @return void
	 */
	public function waitForCondition($jsSnippet, $timeout=NULL, $message=NULL) {
		if (is_null($message)) {
			$message = sprintf("Waiting for condition '%s' timed out", $jsSnippet);
			if (!is_null($timeout)) {
				$message .= " after $timeout seconds";
			}
		}
		if (!$this->getHelperWait()->waitForCondition($jsSnippet, $timeout)) {
			$this->getTest()->fail($message);
		}
	}

	public function waitForVisible($locator, $timeout=NULL, $message=NULL) {#
		if (is_null($message)) {
			$message = sprintf("Waiting for element '%s' visible timed out", $this->getHelperCommon()->element2String($locator));
			if (!is_null($timeout)) {
				$message .= " after $timeout seconds";
			}
		}
		if (!$this->getHelperWait()->waitForElementVisible($locator, $timeout)) {
			$this->getTest()->fail($message);
		}
	}

	public function waitForNotVisible($locator, $timeout=NULL, $message=NULL) {
		if (is_null($message)) {
			$message = sprintf("Waiting for element '%s' not visible timed out", $this->getHelperCommon()->element2String($locator));
			if (!is_null($timeout)) {
				$message .= " after $timeout seconds";
			}
		}
		if (!$this->getHelperWait()->waitForElementNotVisible($locator, $timeout)) {
			$this->getTest()->fail($message);
		}
	}

	/**
	 * Auto-detect element
	 *
	 * @param string|array|WebDriver_Element $element
	 * @return WebDriver_Element
	 */
	public function getElement($element) {
		return $this->getHelperCommon()->getElement($element);
	}

	/**
	 * Get page title
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->getHelperCommon()->getTitle();
	}

	/**
	 * Get text
	 *
	 * @param string|array|WebDriver_Element $element
	 * @return string
	 */
	public function getText($element) {
		return $this->getHelperCommon()->getText($element);
	}

	/**
	 * Get browser url
	 *
	 * @return string
	 * @throws Exception
	 */
	public function getBrowserUrl() {
		if (empty($this->browserUrl)) {
			// $this->browserUrl = Menta_ConfigurationPhpUnitVars::getInstance()->getValue('testing.maindomain');
			throw new Exception('browserUrl has not been set.');
		}
		return $this->browserUrl;
	}

	/**
	 * Open an url prefixed with the previously configured browserUrl
	 *
	 * @param string $url
	 * @return WebDriver_Base
	 */
	public function open($url) {
		if (!preg_match('/^https?:/i', $url)) {
			$url = $this->getBrowserUrl() . $url;
		}
		return $this->getSession()->open($url);
	}

	public function start() {
		// session handling is controlled different now
	}
	public function stop() {
		// session handling is controlled different now
	}
	
	public function windowFocus() {
		$this->getHelperCommon()->focusWindow();
	}
	public function windowMaximize() {
		$this->getHelperCommon()->resizeBrowserWindow();
	}

	public function waitForPageToLoad() {
		// TODO: find an appropriate solution to this
		// http://seleniumhq.org/docs/appendix_migrating_from_rc_to_webdriver.html#waitforpagetoload-returns-too-soon
		sleep(5);
	}

	public function getEval($jsSnippet) {
		return $this->getHelperCommon()->getEval($jsSnippet);
	}

	/**
	 * Click on an element
	 *
	 * @param string|array|WebDriver_Element $element
	 * @return void
	 */
	public function click($element) {
		return $this->getHelperCommon()->click($element);
	}

	/**
	 * Type something
	 *
	 * @param string|array|WebDriver_Element $element
	 * @param $text
	 * @return void
	 */
	public function type($element, $text) {
		return $this->getHelperCommon()->type($element, $text);
	}

	/**
	 * Type text into an input field
	 * Reset first and click outside afterwards
	 *
	 * @param string|array|WebDriver_Element $element
	 * @param string $text
	 * @return void
	 */
	public function typeAndLeave($element, $text) {
		return $this->getHelperCommon()->type($element, $text, true, true);
	}

	/**
	 * Select an option of a select box
	 * Option can be specified via
	 * - "value=<value>" -or-
	 * - "label=<label>"
	 *
	 * @throws Exception
	 * @param string|array|WebDriver_Element $element
	 * @param string $option
	 * @return void
	 */
	public function select($element, $option) {
		$this->getHelperCommon()->select($element, $option);
	}

	public function fireEvent($element, $event) {
		// do nothing?!
		// TODO
	}

	/**
	 * Get value
	 *
	 * @param string|array|WebDriver_Element $element
	 * @return string
	 */
	public function getValue($element) {
		$element = $this->getElement($element);
		return $element->getAttribute('value');
	}

	/**
	 * Check if element is visible
	 *
	 * @param string|array|WebDriver_Element $element
	 * @return bool
	 */
	public function isVisible($element) {
		return $this->getHelperCommon()->isVisible($element);
	}

	/**
	 * Get selected label
	 *
	 * @param string|array|WebDriver_Element $element
	 * @return bool|string
	 */
	public function getSelectedLabel($element) {
		return $this->getHelperCommon()->getSelectedLabel($element);

	}

	/**
	 * Get selected value
	 *
	 * @param string|array|WebDriver_Element $element
	 * @return bool|string
	 */
	public function getSelectedValue($element) {
		return $this->getHelperCommon()->getSelectedValue($element);
	}

	/**
	 * Get first selected option
	 *
	 * @param string|array|WebDriver_Element $element
	 * @return bool|Webdriver_Element
	 */
	public function getFirstSelectedOption($element) {
		return $this->getHelperCommon()->getFirstSelectedOption($element);
	}

	public function getXpathCount($xpath) {
		return $this->getElementCount($xpath); // xpath is supported by parseLocator() and will be autodetected
	}

	public function getElementCount($locator) {
		return $this->getHelperCommon()->getElementCount($locator);
	}

	public function assertTextPresent($text, $message='') {
		$this->getHelperAssert()->assertTextPresent($text, $message);
	}

	public function assertTextNotPresent($text, $message='') {
		$this->getHelperAssert()->assertTextNotPresent($text, $message);
	}

	/**
	 * @param string $element
	 */
	public function clickAndWait($element) {
		$this->click($element);
		$this->waitForPageToLoad();
	}

	public function assertElementPresent($element, $message='') {
		$this->getHelperAssert()->assertElementPresent($element, $message);
	}

	public function assertElementNotPresent($element, $message='') {
		$this->getHelperAssert()->assertElementNotPresent($element, $message);
	}

	public function waitForAjaxCompletedJquery() {
		$this->getHelperWait()->waitForCondition('return (jQuery.active == 0)');
	}

	public function waitForAjaxCompletedPrototype() {
		$this->getHelperWait()->waitForCondition('return (Ajax.activeRequestCount == 0)');
	}

	/**
	 * @param string $clickElement
	 * @param string $waitForElementAfterClick
	 * @param int $timeout timeout in seconds
	 */
	public function clickAndWaitAjax($clickElement, $waitForElementAfterClick, $timeout=NULL) {
		$this->getTest()->assertTrue($this->isElementPresent($clickElement), 'element: ' . $clickElement . ' is not present' );
		$this->click($clickElement);
		$this->waitForElementPresent($waitForElementAfterClick, $timeout);
	}

	/**
	 * Assert element containts text
	 *
	 * @param string|array|WebDriver_Element $element
	 * @param string $text
	 * @param string $message
	 * @return void
	 */
	public function assertElementContainsText($element, $text, $message='') {
		$this->getHelperAssert()->assertElementContainsText($element, $text, $message);
	}


	/* Convenience methods for code completion */

	/**
	 * @return Menta_Component_Helper_Common
	 */
	public function getHelperCommon() {
		return Menta_ComponentManager::get('Menta_Component_Helper_Common');
	}

	/**
	 * @return Menta_Component_Helper_Wait
	 */
	public  function getHelperWait() {
		return Menta_ComponentManager::get('Menta_Component_Helper_Wait');
	}

	/**
	 * @return Menta_Component_Helper_Assert
	 */
	public  function getHelperAssert() {
		return Menta_ComponentManager::get('Menta_Component_Helper_Assert');
	}

}
