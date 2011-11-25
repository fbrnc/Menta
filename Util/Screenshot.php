<?php

/**
 * Screenshot
 */
class Menta_Util_Screenshot {

	const TYPE_INFO = 'info';
	const TYPE_ERROR = 'error';

	protected $time;

	protected $trace;

	protected $type = Menta_Util_Screenshot::TYPE_INFO;

	protected $title;

	protected $description;

	protected $location;

	protected $base64Image;

	/**
	 * Write image to disk
	 *
	 * @param string $filename
	 * @return string path of the written image
	 * @throws Exception
	 */
	public function writeToDisk($filename) {
		if (empty($this->base64Image)) {
			throw new Exception('No base64Image available');
		}
		if (empty($filename)) {
			throw new Exception('No filename set');
		}
		$res = file_put_contents($filename, base64_decode($this->base64Image));
		if ($res === false) {
			throw new Exception("File '$filename' could not be written");
		}
	}

	/**
	 * Clean trace
	 *
	 * @param array $trace
	 * @return array cleaned array
	 */
	public function cleanTrace(array $trace) {
		$path = array();
		foreach ($trace as $dat) {
			$tmp = '';
			if (isset($dat['class'])) $tmp .= $dat['class'];
			if (isset($dat['type'])) $tmp .= $dat['type'];
			if (isset($dat['function'])) $tmp .= $dat['function'];
			$tmp .= '#';
			if (isset($dat['line'])) $tmp .= $dat['line'];
			$path[] = $tmp;
		}
		return $path;
	}

	public function setBase64Image($base64Image) {
		$this->base64Image = $base64Image;
	}

	public function getBase64Image() {
		return $this->base64Image;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setLocation($location) {
		$this->location = $location;
	}

	public function getLocation() {
		return $this->location;
	}

	public function setTime($time) {
		$this->time = $time;
	}

	public function getTime() {
		return $this->time;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTrace($trace) {
		$this->trace = $this->cleanTrace($trace);
	}

	public function getTrace() {
		return $this->trace;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function getType() {
		return $this->type;
	}

}