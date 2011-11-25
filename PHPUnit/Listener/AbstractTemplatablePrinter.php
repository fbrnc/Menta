<?php
require_once 'PHPUnit/Framework/TestListener.php';

/**
 * Templatable printer
 *
 * @author Fabrizio Branca
 */
abstract class Menta_PHPUnit_Listener_AbstractTemplatablePrinter extends PHPUnit_Util_Printer {

	/**
	 * @var string
	 */
	protected $templateFile;

	/**
	 * @var array
	 */
	protected $additionalFiles = array();

	/**
	 * @var string
	 */
	protected $targetFile;

	/**
	 * @var string
	 */
	protected $viewClass = 'Menta_Util_View';

	/**
	 * Constructor
	 *
	 * Parameter can be set in the phpunit xml configuration file:
	 * <code>
	 * <listeners>
	 * 		<listener class="..." file="...">
	 *			<arguments>
	 * 				<string>...</string><!-- targetFile -->
	 *
	 * 				<string>...</string><!-- directory -->
	 *
	 * 				<array>
	 * 					<element key="js/handle.gif">
	 * 						<string>.../Resources/Templates/img/handle.gif</string>
	 *					<element>
	 * 					<element key="js/jquery.beforeafter-1.4.min.js">
	 * 						<string>###MENTA_ROOTDIR###/PHPUnit/Listener/Resources/img/jquery.beforeafter-1.4.min.js</string>
	 *					<element>
	 *					<!-- ... -->
	 * 				</array>
	 * 			</arguments>
	 * 		</listener>
	 * </listeners>
	 * </code>
	 *
	 * @param string $targetFile
	 * @param string $templateFile
	 * @param array $additionalFiles
	 * @throws Exception
	 * @author Fabrizio Branca
	 */
	public function __construct($targetFile=NULL, $templateFile=NULL, array $additionalFiles=NULL) {
		if (!is_null($targetFile)) {
			$this->targetFile = $targetFile;
		}
		$dir = dirname($this->targetFile);
		if (!is_dir($dir)) {
			throw new Exception("Target dir '$dir' does not exist");
		}
		if (!is_null($templateFile)) {
			$this->templateFile = $templateFile;
		}
		$this->templateFile = str_replace('###MENTA_ROOTDIR###', MENTA_ROOTDIR, $this->templateFile);
		if (empty($this->templateFile)) {
			throw new Exception('No template file defined');
		}
		if (!is_null($additionalFiles)) {
			$this->additionalFiles = $additionalFiles;
		}
		parent::__construct($this->targetFile);
	}

	/**
	 * Flush: Copy images and additional files to folder and generate index file using a template
	 *
	 * This method is called once after all tests have been processed.
	 * HINT: The flush method is only called if the TestListener inherits from PHPUnit_Util_Printer
	 *
	 * @param array $templateVars
	 * @return void
	 * @author Fabrizio Branca
	 */
	public function flush(array $templateVars=array()) {
		$this->copyAdditionalFiles();

		$className = $this->viewClass;
		$view = new $className($this->templateFile);
		foreach ($templateVars as $key => $value) {
			$view->assign($key, $value);
		}
		$this->write($view->render());

		return parent::flush();
	}

	/**
	 * Copy additional files
	 *
	 * @return void
	 * @author Fabrizio Branca
	 * @throws Exception
	 */
	protected function copyAdditionalFiles() {
		$dir = dirname($this->targetFile);
		foreach ($this->additionalFiles as $target => $source) {

			$target = str_replace('###MENTA_ROOTDIR###', MENTA_ROOTDIR, $target);
			$source = str_replace('###MENTA_ROOTDIR###', MENTA_ROOTDIR, $source);

			$targetPath = $dir . DIRECTORY_SEPARATOR . $target;

			// TODO: filter ".."

			$pathinfo = pathinfo($targetPath);
			if (!is_dir($pathinfo['dirname'])) {
				mkdir($pathinfo['dirname'], 0777, true);
			}

			$res = copy($source, $targetPath);
			if ($res === false) {
				throw new Exception("Error while copying file $source to $target");
			}
		}
	}

}

