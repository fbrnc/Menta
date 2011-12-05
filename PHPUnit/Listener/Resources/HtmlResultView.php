<?php

/**
 * View helper for HTML Result view
 *
 * @author Fabrizio Branca
 */
class Menta_PHPUnit_Listener_Resources_HtmlResultView extends Menta_Util_View {

	/**
	 * Print test
	 *
	 * @param array $test
	 * @param null $name
	 * @return string
	 */
	public function printTest(array $test, $name=NULL) {
		$testName = $name ? $name : $test['testName'];
		$roundPrecision = ($test['time'] < 10) ? 2 : 0;
		$result = '';
		$result .= '<div class="test '.$this->getStatusName($test['status']).'">';
			$result .= '<div class="duration">'.round($test['time'], $roundPrecision).'s</div>';
			$result .= '<h2>'.$this->shorten($testName).'</h2>';
			$result .= '<div class="content">';

				if ($test['exception'] instanceof Exception) {
					$e = $test['exception']; /* @var $e Exception */
					$result .= '<div class="exception">';
						$result .= '<i>'. nl2br($this->escape(PHPUnit_Util_Filter::getFilteredStacktrace($e))) . '</i>'."<br />\n";
						$result .= '<pre>' . $this->escape(PHPUnit_Framework_TestFailure::exceptionToString($e)) . '</pre>';
					$result .= '</div><!-- exception -->';
				}

				if (isset($test['screenshots'])) {
					$result .= '<div class="screenshots">';
					$result .= $this->printScreenshots($test['screenshots']);
					$result .= '</div><!-- screenshots -->';
				}
		
			$result .= '</div><!-- content -->';
		$result .= '</div><!-- test -->';
		return $result;
	}

	/**
	 * Print screenshots
	 *
	 * @param array $screenshots
	 * @return string
	 */
	protected function printScreenshots(array $screenshots) {
		$result = '';
		$directory = $this->get('basedir');
		$result .= '<ul class="screenshots-list">';
		foreach ($screenshots as $screenshot) { /* @var $screenshot Menta_Util_Screenshot */
			$result .= '<li class="screenshot">';

			try {
				$uniqId = md5(uniqid(rand(), TRUE));
				$filenName = 'screenshot_' . $uniqId . '.png';
				$thumbnailName = 'screenshot_' . $uniqId . '_thumb.png';

				$screenshot->writeToDisk($directory . DIRECTORY_SEPARATOR . $filenName);

				// create thumbnail
				$simpleImage = new Menta_Util_SimpleImage($directory . DIRECTORY_SEPARATOR . $filenName);
				$simpleImage->resizeToWidth(100)->save($directory . DIRECTORY_SEPARATOR . $thumbnailName, IMAGETYPE_PNG);

				$result .= '<a title="'.$screenshot->getTitle().'" href="'.$filenName.'">';
					$result .= '<img src="'.$thumbnailName.'" width="100" />';
				$result .= '</a>';
			} catch (Exception $e) {
				$result .= 'EXCEPTION: '.$e->getMessage();
			}
			$result .= '</li>';
		}
		$result .= '</ul>';
		return $result;
	}

	/**
	 * Print tests
	 *
	 * @param array $tests
	 * @return string
	 */
	public function printTests(array $tests) {
		$result = '<div class="wrapper tests">';
		foreach ($tests as $key => $values) {
			if ($key == '__datasets') {
				$result .= '<div class="wrapper dataset">';
				foreach ($values as $dataSetName => $test) {
					$result .= $this->printTest($test, $dataSetName);
				}
				$result .= '</div><!-- dataset -->';
			} else {
				$result .= $this->printTest($values);
			}
		}
		$result .= '</div><!-- tests -->';
		return $result;
	}

	/**
	 * Print browsers
	 *
	 * @param array $browsers
	 * @return string
	 */
	public function printBrowsers(array $browsers) {
		$result = '<div class="wrapper browsers">';
		foreach ($browsers as $browserName => $values) {
			$result .= '<div class="browser">';
			$result .= '<h2>'.$browserName.'</h2>';
			$result .= $this->printResult($values);
			$result .= '</div><!-- browser -->';
		}
		$result .= '</div><!-- browsers -->';
		return $result;
	}

	/**
	 * Print suites
	 *
	 * @param array $suites
	 * @return string
	 */
	public function printSuites(array $suites) {
		$result = '<div class="wrapper suites">';
		foreach ($suites as $suiteName => $suite) {
			$result .= '<div class="suite">';
			$result .= '<h2>'.$suiteName.'</h2>';
			$result .= $this->printResult($suite);
			$result .= '</div><!-- suite -->';
		}
		$result .= '</div><!-- suites -->';
		return $result;
	}

	/**
	 * Print result
	 *
	 * @throws Exception
	 * @param array $array
	 * @return string
	 */
	public function printResult(array $array) {
		$result = '';
		foreach ($array as $key => $value) {
			if ($key == '__browsers') {
				$result .= $this->printBrowsers($value);
			} elseif ($key == '__suites') {
				$result .= $this->printSuites($value);
			} elseif ($key == '__tests') {
				$result .= $this->printTests($value);
			} else {
				throw new Exception("Unexpected key $key");
			}
		}
		return $result;
	}

	/**
	 * Shorten name by removing class name part
	 *
	 * @param string $name
	 * @return string
	 */
	public function shorten($name) {
		return preg_replace('/.*::/', '', $name);
	}

	/**
	 * Get speaking status name
	 *
	 * @param int $status
	 * @return string
	 */
	public function getStatusName($status) {
		$names = array(
			PHPUnit_Runner_BaseTestRunner::STATUS_PASSED => 'passed',
			PHPUnit_Runner_BaseTestRunner::STATUS_SKIPPED => 'skipped',
			PHPUnit_Runner_BaseTestRunner::STATUS_INCOMPLETE => 'incomplete',
			PHPUnit_Runner_BaseTestRunner::STATUS_FAILURE => 'failed',
			PHPUnit_Runner_BaseTestRunner::STATUS_ERROR => 'error',
		);
		return $names[$status];
	}
		
}