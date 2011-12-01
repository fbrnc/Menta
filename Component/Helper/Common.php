<?php
/**
 * Common helper
 *
 * @author Fabrizio Branca
 * @since 2011-11-18
 */
class Menta_Component_Helper_Common extends Menta_Component_Abstract {

	/**
	 * Parse locator
	 *
	 * Currently detected:
	 * - xpath (if it contains a "/")
	 * - id=
	 * - <string> (will be used as id)
	 *
	 * @throws Exception
	 * @param $locator
	 * @return array
	 */
	public function parseLocator($locator) {
		if (is_array($locator) && isset($locator['using']) && isset($locator['value'])) {
			// already the correct element => do nothing
		} elseif (substr($locator, 0, 6) == 'xpath=') {
			$locator = array('using' => WebDriver_Container::XPATH, 'value' => substr($locator, 6));
		} elseif (strpos($locator, '/') !== false) {
			$locator = array('using' => WebDriver_Container::XPATH, 'value' => $locator);
		} elseif (substr($locator, 0, 3) == 'id=') {
			$locator = array('using' => WebDriver_Container::ID, 'value' => substr($locator, 3));
		} elseif (substr($locator, 0, 4) == 'css=') {
			$locator = array('using' => WebDriver_Container::CSS_SELECTOR, 'value' => substr($locator, 4));
		} elseif (substr($locator, 0, 5) == 'link=') {
			$locator = array('using' => WebDriver_Container::LINK_TEXT, 'value' => substr($locator, 5));
		} elseif (is_string($locator)) {
			$locator = array('using' => WebDriver_Container::ID, 'value' => $locator);
		} else {
			throw new Exception('Could not parse locator');
		}
		// TODO: auto detect other locator strategies from string
		// check http://release.seleniumhq.org/selenium-core/1.0/reference.html#locators for a complete list of Selenium 1 strategies
		return $locator;
	}

	/**
	 * Auto-detect element
	 *
	 * @throws Exception
	 * @param $element
	 * @return WebDriver_Element
	 */
	public function getElement($element) {
		if ($element instanceof WebDriver_Element) {
			// already the correct element => do nothing
		} else {
			$element = $this->getSession()->element($this->parseLocator($element));
		}
		if (!$element instanceof WebDriver_Element) {
			throw new Exception("Element '$element' not found");
		}
		return $element;
	}

	/**
	 * Convert an element to a string for fail message purposes
	 *
	 * @param $element
	 * @return string
	 */
	public function element2String($element) {
		if (is_string($element)) {
			// do nothing
		} elseif (is_array($element) && isset($element['using']) && isset($element['value'])) {
			$element = $element['using'] . '=' . $element['value'];
		} elseif ($element instanceof WebDriver_Element) {
			/* @var $element WebDriver_Element */
			$element = $element->__toString();
		} else {
			$element = '[INVALID ELEMENT LOCATOR]';
		}
		return $element;
	}

	/**
	 * Check if element is present
	 *
	 * @param $element
	 * @return bool
	 */
	public function isElementPresent($element) {
		$locator = $this->parseLocator($element);
		return (count($this->getSession()->elements($locator)) > 0);
	}

	/**
	 * Check if element is visible
	 *
	 * @param $element
	 * @return bool
	 */
	public function isVisible($element) {
		return $this->getElement($element)->displayed();
	}

	/**
	 * Check if text is present
	 *
	 * @param $text
	 * @return bool
	 */
	public function isTextPresent($text) {
		return (strpos($this->getSession()->source(), $text) !== false);
	}

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->getSession()->title();
	}

	/**
	 * Get eval (run javascript on client)
	 *
	 * @param $jsSnippet
	 * @param array $args
	 * @return mixed (snippet return value)
	 */
	public function getEval($jsSnippet, array $args=array()) {
		// no tricks needed in selenium 2
		$jsSnippet = preg_replace('/^.*getUserWindow\(\)\./', '', $jsSnippet);

		// ... but the snippet needs to return something (like a function call)
		if (!preg_match('/^return /', $jsSnippet)) {
			$jsSnippet = 'return ' . $jsSnippet;
		}

		try {
			$result = $this->getSession()->execute(array('script'=> $jsSnippet, 'args' => $args));
		} catch (Exception $e) {
			throw new Exception("Error while executing snippet '$jsSnippet'");
		}
		return $result;
	}

	/**
	 * Resize browser window
	 *
	 * @param int $width
	 * @param int $height
	 * @param int $x
	 * @param int $y
	 * @param string $windowHandle
	 * @return void
	 */
	public function resizeBrowserWindow($width=1280, $height=1024, $x=0, $y=0, $windowHandle='main') {
		$this->getSession()->window($windowHandle)->position(array('x' => $x, 'y' => $y));
		$this->getSession()->window($windowHandle)->size(array('width' => $width, 'height' => $height));
	}

	/**
	 * Focus window
	 *
	 * @param string $windowHandle
	 * @return void
	 */
	public function focusWindow($windowHandle='main') {
		$this->getSession()->window($windowHandle);
	}

	/**
	 * Count elements
	 *
	 * @param $locator
	 * @return int
	 */
	public function getElementCount($locator) {
		$locator = $this->parseLocator($locator);
		$elements = $this->getSession()->elements($locator);
		return count($elements);
	}

	/**
	 * Get text
	 *
	 * @param string|array|WebDriver_Element $element
	 * @return string
	 */
	public function getText($element) {
		return $this->getElement($element)->text();
	}

	/**
	 * Click on an element
	 *
	 * @param string|array|WebDriver_Element $element
	 * @return void
	 */
	public function click($element) {
		return $this->getElement($element)->click();
	}

	/**
	 * Type something into an input box
	 *
	 * @param tring|array|WebDriver_Element $element
	 * @param string $text
	 * @param bool $resetContent
	 * @param bool $leaveFieldAfterwards
	 * @return void
	 */
	public function type($element, $text, $resetContent=false, $leaveFieldAfterwards=false) {
		$element = $this->getElement($element);
		if ($resetContent) {
			// got to the end, mark everything to the beginning to overwrite existing content
			$element->value(array('value' => array(WebDriver_Keys::End . WebDriver_Keys::Shift . WebDriver_Keys::Home)));
		}
		$element->value(array('value' => array($text)));
		if ($leaveFieldAfterwards) {
			$this->click('//body');
		}
	}

	/**
	 * Select an option of a select box
	 * Option can be specified via
	 * - "value=<value>" -or-
	 * - "label=<label>"
	 *
	 * @param string|array|WebDriver_Element $element
	 * @param string $option
	 * @throws Exception
	 */
	public function select($element, $option) {
		$element = $this->getElement($element);
		if (substr($option, 0, 6) == 'value=') {
			$option = substr($option, 6);
			$option = $element->element(WebDriver_Container::XPATH, 'option[@value="'.$option.'"]');
		} elseif (substr($option, 0, 6) == 'label=') {
			$option = substr($option, 6);
			$option = $element->element(WebDriver_Container::XPATH, 'option[normalize-space(text())="'.$option.'"]');
		} else {
			throw new Exception('Expecting label to begin with "label=" or "value="');
		}
		$option->click();
	}

}

