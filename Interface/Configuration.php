<?php
/**
 * Configuration interface
 *
 * @author Fabrizio Branca
 * @since 2011-11-24
 */
interface Menta_Interface_Configuration {

	/**
	 * Get a configuration value by key
	 *
	 * @abstract
	 * @param string $key
	 * @return mixed
	 */
	public function getValue($key);

	/**
	 * Check if a configuration key is set
	 *
	 * @abstract
	 * @param string $key
	 * @return bool
	 */
	public function issetKey($key);

}
