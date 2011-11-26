<?php
/**
 * Selenium1_Testcase
 *
 * @author Fabrizio Branca
 * @since 2011-11-18
 */

/**
 * Update this index using:
 * cat Menta/Component/Selenium1Facade.php | grep 'public function' | sed 's/^.*public function / * @method /' | sed 's/\w*{.*\?//'
 *
 * @method setBrowserUrl($baseUrl)
 * @method assertTitle($title)
 * @method isElementPresent($element)
 * @method isTextPresent($text)
 * @method waitForElementPresent($locator, $timeout=null, $message=null)
 * @method waitForElementNotPresent($locator, $timeout=null, $message=null)
 * @method waitForTextPresent($text, $timeout=null, $message=null)
 * @method waitForTextNotPresent($text, $timeout=null, $message=NULL)
 * @method waitForCondition($jsSnippet, $timeout=NULL, $message=NULL)
 * @method waitForVisible($locator, $timeout=NULL, $message=NULL)
 * @method waitForNotVisible($locator, $timeout=NULL, $message=NULL)
 * @method getElement($element)
 * @method getTitle()
 * @method getText($element)
 * @method getBrowserUrl()
 * @method open($url)
 * @method start()
 * @method stop()
 * @method windowFocus()
 * @method windowMaximize()
 * @method waitForPageToLoad()
 * @method getEval($jsSnippet)
 * @method click($element)
 * @method type($element, $text)
 * @method select($element, $option)
 * @method fireEvent($element, $event)
 * @method getValue($element)
 * @method isVisible($element)
 * @method getSelectedLabel($element)
 * @method getSelectedValue($element)
 * @method getFirstSelectedOption($element)
 * @method getXpathCount($xpath)
 * @method getElementCount($locator)
 * @method assertTextPresent($text, $message='')
 * @method assertTextNotPresent($text, $message='')
 * @method clickAndWait($element)
 * @method assertElementPresent($element, $message='')
 * @method assertElementNotPresent($element, $message='')
 * @method waitForAjaxCompletedJquery()
 * @method waitForAjaxCompletedPrototype()
 * @method clickAndWaitAjax($clickElement, $waitForElementAfterClick, $timeout=NULL)
 * @method typeAndLeave($element, $text)
 * @method assertElementContainsText($element, $text, $message='')
 */
abstract class Menta_PHPUnit_Testcase_Selenium1 extends Menta_PHPUnit_Testcase_Selenium2 {

	protected $captureScreenshotOnFailure = false;

	/**
	 * @var string
	 */
	protected $testId;

	/**
	 * @var Menta_Component_Selenium1Facade
	 */
	protected $selenium1Facade;

	/**
	 * @param  string $name
	 * @param  array  $data
	 * @param  string $dataName
	 * @param  array  $browser
	 * @throws InvalidArgumentException
	 */
	public function __construct($name = NULL, array $data = array(), $dataName = '', array $browser = array()) {
		$this->testId = md5(uniqid(rand(), TRUE));
		parent::__construct($name, $data, $dataName, $browser);
	}

	/**
	 * Get test id
	 *
	 * @return string
	 */
	public function getTestId() {
		return $this->testId;
	}

	/**
	 * @return Menta_ConfigurationPhpUnitVars
	 */
	public function getConfiguration() {
		return Menta_ConfigurationPhpUnitVars::getInstance();
	}

	public function setBrowserUrl($baseUrl) {
		$this->getSelenium1Facade()->setBrowserUrl($baseUrl);
	}

	/**
	 * Get selenium1 api
	 *
	 * @return Menta_Component_Selenium1Facade
	 */
	public function getSelenium1Facade() {
		if (is_null($this->selenium1Facade)) {
			$this->selenium1Facade = Menta_ComponentManager::get('Menta_Component_Selenium1Facade');
			$this->selenium1Facade->setTest($this);
		}
		return $this->selenium1Facade;
	}

	/**
	 * Delegate method calls to the selenium 1 api.
	 *
	 * @param  string $command
	 * @param  array  $arguments
	 * @return mixed
	 */
	public function __call($command, $arguments) {
		// file_put_contents('debug.txt', var_export($command, 1) ."\n", FILE_APPEND);
		if (!method_exists($this->getSelenium1Facade(), $command)) {
			throw new Exception("Command $command is not implemented in the selenium1 api wrapper class");
		}

		$result = call_user_func_array(array($this->getSelenium1Facade(), $command), $arguments);
		return $result;
	}

}
