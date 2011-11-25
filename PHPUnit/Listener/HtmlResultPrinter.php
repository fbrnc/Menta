<?php
/**
 * HTML result printer
 *
 * @author Fabrizio Branca
 * @since 2011-11-13
 */
class Menta_PHPUnit_Listener_HtmlResultPrinter extends Menta_PHPUnit_Listener_AbstractTemplatablePrinter implements PHPUnit_Framework_TestListener {

	/**
	 * @var string
	 */
	protected $templateFile = '###MENTA_ROOTDIR###/PHPUnit/Listener/Resources/Templates/HtmlResultTemplate.php';

	/**
	 * @var array
	 */
	protected $additionalFiles = array();

	protected $lastResult;

	protected $lastStatus;

	protected $level = 0;

	protected $suiteStack = array();

	protected $results = array();

	protected $count = array();

	protected $viewClass = 'Menta_PHPUnit_Listener_Resources_HtmlResultView';

	public function startTest(PHPUnit_Framework_Test $test) {}

	public function addError(PHPUnit_Framework_Test $test, Exception $e, $time) {
		$this->lastResult = $e;
		$this->lastStatus = PHPUnit_Runner_BaseTestRunner::STATUS_ERROR;
	}
	public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time) {
		$this->lastResult = $e;
		$this->lastStatus = PHPUnit_Runner_BaseTestRunner::STATUS_FAILURE;
	}
	public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time) {
		$this->lastResult = $e;
		$this->lastStatus = PHPUnit_Runner_BaseTestRunner::STATUS_INCOMPLETE;
	}
	public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time) {
		$this->lastResult = $e;
		$this->lastStatus = PHPUnit_Runner_BaseTestRunner::STATUS_SKIPPED;
	}

	public function endTest(PHPUnit_Framework_Test $test, $time) {

		$testName = PHPUnit_Util_Test::describe($test);

		// store in result array
		$currentArray =& $this->results;
		foreach ($this->suiteStack as $suiteName) {
			$colonPos = strpos($suiteName, ': ');
			if ($colonPos !== false) {
				$browser = substr($suiteName, $colonPos+2);
				$suiteName = substr($suiteName, 0, $colonPos);
				// $currentArray =& $currentArray['__suites'][$suiteName]['__browsers'][$browser];
				$currentArray =& $currentArray['__browsers'][$browser];
			} else {
				$currentArray =& $currentArray['__suites'][$suiteName];
			}
		}

		if (is_null($this->lastStatus)) {
			$this->lastStatus = PHPUnit_Runner_BaseTestRunner::STATUS_PASSED;
		}

		$result = array(
			'testName' => $testName,
			'time' => $time,
			'exception' => $this->lastResult,
			'status' => $this->lastStatus
		);

		if ($test instanceof Menta_Interface_ScreenshotTestcase) { /* @var $test Menta_Interface_ScreenshotTestcase */
			$screenshots = $test->getScreenshots();
			if (is_array($screenshots) && count($screenshots) > 0) {
				$result['screenshots'] = $screenshots;
			}
		}

		if (isset($this->count[$this->lastStatus])) {
			$this->count[$this->lastStatus]++;
		} else {
			$this->count[$this->lastStatus] = 1;
		}

		$dataSetPos = strpos($testName, ' with data set ');
		if ($dataSetPos !== false) {
			$dataSet = substr($testName, $dataSetPos+5);
			$dataSet = ucfirst(trim($dataSet));
			$testName = substr($testName, 0, $dataSetPos);
			$currentArray['__tests']['__datasets'][$dataSet] = $result;
		} else {
			$currentArray['__tests'][$testName] = $result;
		}

		$this->lastResult = NULL;
		$this->lastStatus = NULL;
	}

	public function startTestSuite(PHPUnit_Framework_TestSuite $suite) {
		$this->level++;
		$name = PHPUnit_Util_Test::describe($suite);
		if (empty($name)) {
			//$name = get_class($suite);
			$name = '-';
		}
		$this->suiteStack[] = $name;
	}

	public function endTestSuite(PHPUnit_Framework_TestSuite $suite) {
		$this->level--;
		array_pop($this->suiteStack);
	}

	/**
	 * Flush: Copy images and additional files to folder and generate index file using a template
	 *
	 * This method is called once after all tests have been processed.
	 * HINT: The flush method is only called if the TestListener inherits from PHPUnit_Util_Printer
	 *
	 * @return void
	 * @author Fabrizio Branca
	 */
	public function flush() {
		ksort($this->count);
		$sum = array_sum($this->count);
		$percentages = array();
		foreach($this->count as $key => $value) {
			$percentages[$key] = 100 * $value/$sum;
		}
		return parent::flush(array(
			'basedir' => dirname($this->targetFile),
			'results' => $this->results,
			'count' => $this->count,
			'percentages' => $percentages
		));
	}

}

