<?php
/**
 * Simple Image
 *
 * @author Fabrizio Branca
 * @author Simon Jarvis (original version)
 * @see: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details:
 * http://www.gnu.org/licenses/gpl.html
 */
class Menta_Util_SimpleImage {

	/**
	 * @var resource
	 */
	protected $image;

	protected $image_type;

	/**
	 * Constructor
	 *
	 * @throws Exception
	 * @param string $filename
	 */
	public function __construct($filename) {
		if (!is_file($filename)) {
			throw new InvalidArgumentException("File '$filename' not found");
		}
		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		switch($this->image_type) {
			case IMAGETYPE_JPEG: $this->image = imagecreatefromjpeg($filename); break;
			case IMAGETYPE_GIF:  $this->image = imagecreatefromgif($filename); break;
			case IMAGETYPE_PNG:  $this->image = imagecreatefrompng($filename); break;
			default: throw new Exception("Image type '{$this->image_type}' is invalid");
		}
		if ($this->image === false) {
			throw new Exception("Error while creating image from '$filename'");
		}
	}

	/**
	 * Save image to file
	 *
	 * @throws Exception
	 * @param $filename
	 * @param int $image_type
	 * @param int $compression
	 * @param null $permissions
	 * @return Menta_Util_SimpleImage
	 */
	public function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
		switch($image_type) {
			case IMAGETYPE_JPEG: $res = imagejpeg($this->image, $filename, $compression); break;
			case IMAGETYPE_GIF:  $res = imagegif($this->image, $filename); break;
			case IMAGETYPE_PNG:  $res = imagepng($this->image, $filename); break;
			default: throw new Exception("Image type '$image_type' is invalid");
		}
		if ($res === false) {
			throw new Exception("Error while saving file '$filename' (type: '$image_type')");
		}
		if (!is_null($permissions)) {
			chmod($filename, $permissions);
		}
		return $this;
	}

	/**
	 * Output raw image stream directly
	 *
	 * @param int $image_type
	 * @return Menta_Util_SimpleImage
	 */
	public function output($image_type=IMAGETYPE_JPEG) {
		switch($image_type) {
			case IMAGETYPE_JPEG: $res = imagejpeg($this->image); break;
			case IMAGETYPE_GIF:  $res = imagegif($this->image); break;
			case IMAGETYPE_PNG:  $res = imagepng($this->image); break;
			default: throw new Exception("Image type '$image_type' is invalid");
		}
		if ($res === false) {
			throw new Exception("Error while outputting raw image (type: '$image_type')");
		}
		return $this;
	}

	/**
	 * Get width
	 *
	 * @return int
	 */
	public function getWidth() {
		return imagesx($this->image);
	}

	/**
	 * Get height
	 *
	 * @return int
	 */
	public function getHeight() {
		return imagesy($this->image);
	}

	/**
	 * Resize to height
	 *
	 * @param int $height
	 * @return Menta_Util_SimpleImage
	 */
	public function resizeToHeight($height) {
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width, $height);
		return $this;
	}

	/**
	 * Resize to width
	 *
	 * @param $width
	 * @return Menta_Util_SimpleImage
	 */
	public function resizeToWidth($width) {
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize($width, $height);
		return $this;
	}

	/**
	 * Scale image by a given factor
	 *
	 * @param $factor
	 * @return Menta_Util_SimpleImage
	 */
	public function scale($factor) {
		$width = $this->getWidth() * $factor / 100;
		$height = $this->getheight() * $factor / 100;
		$this->resize($width, $height);
		return $this;
	}

	/**
	 * Resize image
	 *
	 * @throws InvalidArgumentException
	 * @param int $width
	 * @param int $height
	 * @return Menta_Util_SimpleImage
	 */
	public function resize($width, $height) {
		$width = intval($width);
		$height = intval($height);
		if (empty($width) || empty($height)) {
			throw new InvalidArgumentException('Invalid dimensions');
		}
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
		return $this;
	}

}