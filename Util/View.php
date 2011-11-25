<?php

/**
 * Basic view class
 */
class Menta_Util_View {

	/**
	 * @var array
	 */
	protected $data = array();

	/**
	 * @var string template file
	 */
	protected $templateFile = '';

	/**
	 * Constructor
	 *
	 * @param string $templateFile
	 */
	public function __construct($templateFile) {
		if (!is_file($templateFile)) {
			throw new Exception("Could not find template file $templateFile");
		}
		$this->templateFile = $templateFile;
	}

	/**
	 * Assign data to the view
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function assign($key, $value) {
		$this->data[$key] = $value;
	}

	/**
	 * Get data from the view
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key) {
		return $this->data[$key];
	}

	/**
	 * Render view
	 *
	 * @return string
	 */
	public function render() {
		ob_start();
		include($this->templateFile);
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

}