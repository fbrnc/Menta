<?php
/**
 * Assert helper
 *
 * @author Fabrizio Branca
 * @since 2011-11-18
 */
class Menta_Component_Helper_Assert extends Menta_Component_AbstractTest {

	/**
	 * Assert page title
	 *
	 * @param $title
	 * @param string $message
	 * @return void
	 */
	public function assertTitle($title, $message='') {

		if ($this->getConfiguration()) {
			if ($this->getConfiguration()->issetKey('testing.selenium.titlePrefix')) {
				$title = $this->getConfiguration()->getValue('testing.selenium.titlePrefix') . $title;
			}
			if ($this->getConfiguration()->issetKey('testing.selenium.titleSuffix')) {
				$title .= $this->getConfiguration()->getValue('testing.selenium.titleSuffix');
			}
		}

		$this->getTest()->assertEquals($title, $this->getSession()->title(), $message);
	}

	/**
	 * Assert text present
	 *
	 * @param string $text
	 * @param string $message
	 * @return void
	 */
	public function assertTextPresent($text, $message='') {
		if (empty($message)) {
			$message = "Text '$text' not found";
		}
		$this->getTest()->assertTrue($this->getHelperCommon()->isTextPresent($text), $message);
	}

	/**
	 * Assert text not present
	 *
	 * @param string $text
	 * @param string $message
	 * @return void
	 */
	public function assertTextNotPresent($text, $message='') {
		if (empty($message)) {
			$message = "Text '$text' found";
		}
		$this->getTest()->assertFalse($this->getHelperCommon()->isTextPresent($text), $message);
	}

	/**
	 * Assert element present
	 *
	 * @param string|array|WebDriver_Element $element
	 * @param string $message
	 * @return void
	 */
	public function assertElementPresent($element, $message='') {
		if (empty($message)) {
			$message = sprintf("Element '%s' not found", $this->getHelperCommon()->element2String($element));
		}
		$this->getTest()->assertTrue($this->getHelperCommon()->isElementPresent($element), $message);
	}

	/**
	 * Assert element not present
	 *
	 * @param string|array|WebDriver_Element $element
	 * @param string $message
	 * @param bool $implictWait
	 * @return void
	 */
	public function assertElementNotPresent($element, $message='', $implictWait=false) {

		if (!$implictWait && $this->getConfiguration() && $this->getConfiguration()->issetKey('testing.selenium.timeoutImplicitWait')) {
			$time = $this->getConfiguration()->getValue('testing.selenium.timeoutImplicitWait');
			$this->getSession()->timeouts()->implicit_wait(array('ms' => 0)); // deactivate implicit wait
		}

		if (empty($message)) {
			$message = sprintf("Element '%s' found", $this->getHelperCommon()->element2String($element));
		}
		
		try {
			$elementPresent = $this->getHelperCommon()->isElementPresent($element);
		} catch (Exception $e) {}

		if (!empty($time)) {
			$this->getSession()->timeouts()->implicit_wait(array('ms' => $time)); // reactivate implicit wait
		}

		// "finally" workaround
		if (isset($e)) { throw $e; }

		if ($elementPresent) {
			$this->getTest()->fail($message);
		}
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
		if ($message == '') {
			$message = sprintf('Element "%s" does not contain text "%s"', $this->getHelperCommon()->element2String($element), $text);
		}
		$this->getTest()->assertContains($text, $this->getHelperCommon()->getText($element), $message);
	}

	/**
	 * Get common helper
	 *
	 * @return Menta_Component_Helper_Common
	 */
	protected function getHelperCommon() {
		return Menta_ComponentManager::get('Menta_Component_Helper_Common');
	}


}

