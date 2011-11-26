<?php

/**
 * Static test suite.
 *
 * @author Fabrizio Branca
 */
class Menta_Util_CreateTestSuite {

	/**
	 * Create suite from path by searching (recursive) for all Test(case).php files
	 *
	 * @param string $path
	 * @return PHPUnit_Framework_TestSuite|false
	 */
	public static function createSuiteFromPath($path) {
		$tmpSuite = new PHPUnit_Framework_TestSuite();
		$somethingWasAdded = false;
		$paths = array();
		$files = array();

		// collect paths and files first
		foreach (new DirectoryIterator($path) as $fileInfo) { /* @var $fileInfo SplFileInfo */
			$fileName = $fileInfo->getFilename();
			$pathName = $fileInfo->getPathname();

			if ($fileName[0] == '.') { // exclude ".", "..", ".svn",...
				continue;
			}

			// directories and links pointing to directories
			if ($fileInfo->isDir() || ($fileInfo->isLink() && is_dir($fileInfo->isLink()) )) {
				$paths[] = $pathName;
			} elseif ($fileInfo->isFile()) {
				if ((substr(strtolower($fileName), -12) == 'testcase.php') || (substr(strtolower($fileName), -8) == 'test.php')) {
					$files[] = $pathName;
				} elseif ((substr(strtolower($fileName), -4) == '.php')) {
					if (strpos(strtolower($fileName), 'bootstrap') === false && strpos(strtolower($fileName), 'abstract') === false) {
						echo "WARNING: Found php file that is not a test file: $fileName\n";
					}
				}
			}
		}

		// sort them alphabetically
		sort($paths);
		sort($files);

		// create subsuites for all directories found
		foreach ($paths as $pathName) {
			$subSuite = self::createSuiteFromPath($pathName);
			if ($subSuite) {
				$tmpSuite->addTestSuite($subSuite);
				$somethingWasAdded = true;
			}
		}

		// add tests for all files found
		foreach ($files as $pathName) {
			$output = self::getRelativeRealpath($pathName);
			echo "Added test file: $output\n";
			$tmpSuite->addTestFile($pathName);
			$somethingWasAdded = true;
		}

		return ($somethingWasAdded ? $tmpSuite : false);
	}

	/**
	 * Get relative path
	 *
	 * @static
	 * @param $path
	 * @return mixed|string
	 */
	public static function getRelativeRealpath($path) {
		$path = realpath($path);
		$path = str_replace(getcwd() . DIRECTORY_SEPARATOR, '', $path);
		return $path;
	}

	/**
	 * Creates the suite.
	 *
	 * Run single test or test from special folders by adding
	 * --testFile <path>
	 * or
	 * --testPath <path>
	 * to the phpunit call
	 *
	 * If no parameter is set it takes all tests from the current directory
	 * 
	 * @return PHPUnit_Framework_TestSuite|false
	 */
	public static function suite() {
		global $argv;

		$index = array_search('--testPath', $argv);
		$testPath = ($index !== false) ? $argv[$index+1] : '.';
		$testSuite = self::createSuiteFromPath($testPath);

		if ($testSuite === false) {
			throw new Exception('No testcases were found. Testcase files must end with "TestCase.php" or with "Test.php"');
		}

		echo "\n";

		return $testSuite;
	}
}

