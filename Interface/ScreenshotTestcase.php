<?php
/**
 * Interface for testcase that can take screenshots
 *
 * @author Fabrizio Branca
 * @since 2011-11-20
 */
interface Menta_Interface_ScreenshotTestcase {

	/**
	 * Take a screenshot
	 *
	 * @abstract
	 * @param string $title
	 * @param string $description
	 * @param string $type
	 * @param array $trace
	 * @return return Menta_Util_Screenshot
	 */
	function takeScreenshot($title=NULL, $description=NULL, $type=NULL, array $trace=NULL);

	/**
	 * Get all screenshots that were taken so far
	 *
	 * @return array array of Menta_Util_Screenshot
	 */
	function getScreenshots();

}
