<?php

/**
 * Configuration to get the config from xml files
 *
 * @author Fabrizio Branca
 * 
 * I did a rewrite of the class maintaining its api methods getValue() and issetKey().
 * Now it is possible to use the class completely static.
 * I've cleaned up the messy way how settings from phpunit's configuration file and
 * settings from the default.xml file were merged and overwritten into constants and
 * global variables. Now everything is handled via global variables same way like
 * phpunit internally handles settings set found in //phpunit/php/var.
 * Additionally now it is possible to have access settings set in the phpunit file, that
 * are not set in the default.xml (this was not possible before)
 */
class Menta_ConfigurationPhpUnitVars implements Menta_Interface_Configuration {

	/**
	 * @var Menta_ConfigurationPhpUnitVars
	 */
	protected static $instance;

	/**
	 * @var array
	 */
	protected static $configurationFiles = array();

	/**
	 * @static
	 * @throws InvalidArgumentException
	 * @param $configurationFile
	 * @return void
	 */
	public static function addConfigurationFile($configurationFile) {
		if (!is_file($configurationFile)) {
			throw new InvalidArgumentException("Could not find file '$configurationFile'");
		}
		self::$configurationFiles[] = $configurationFile;
	}

	/**
	 * Get singleton instance
	 *
	 * @return Menta_ConfigurationPhpUnitVars
	 */
	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new Menta_ConfigurationPhpUnitVars();
		}
		return self::$instance;
	}

	/**
	 * Get configuration value
	 *
	 * @throws Exception if key is not found
	 * @param string $key
	 * @return string
	 */
	public function getValue($key) {
		if (empty($GLOBALS[__CLASS__.'_defaultsLoaded'])) {
			$this->loadDefaults();
		}
		if (!$this->issetKey($key)) {
			throw new Exception(sprintf('Could not find configuration key "%s"', $key));
		}
		return $GLOBALS[$key];
	}

	/**
	 * Check if key is set
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function issetKey($key) {
		if (empty($GLOBALS[__CLASS__.'_defaultsLoaded'])) {
			$this->loadDefaults();
		}
		return isset($GLOBALS[$key]);
	}

	/**
	 * Read values from file and stores them into $GLOBALS without overriding existing values
	 *
	 * @return void
	 */
	protected function loadDefaults() {
		foreach (self::$configurationFiles as $xmlfile) {
			if (file_exists($xmlfile)) {
				$xml = simplexml_load_file($xmlfile);
				foreach ($xml->xpath('//phpunit/php/var') as $node) { /* @var $node SimpleXMLElement */
					$key = (string)$node['name'];
					if (!isset($GLOBALS[$key])) { // only set the default value if no other value is set
						$GLOBALS[$key] = (string)$node['value'];
					}
				}
			}
		}
		// storing information to globals instead of static properties. So if globals will
		// be restored this information also gets lost and will trigger reloading of the defaults
		$GLOBALS[__CLASS__.'_defaultsLoaded'] = true;
	}

}