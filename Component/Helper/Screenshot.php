<?php
/**
 * Screenhot helper
 *
 * @author Fabrizio Branca
 * @since 2011-11-18
 */
class Menta_Component_Helper_Screenshot extends Menta_Component_Abstract {

	/**
	 * take screenshot
	 *
	 * @return string
	 */
	public function takeScreenshotToString() {
		$base64Image = $this->getSession()->screenshot();
		return $base64Image;
	}

}

