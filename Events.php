<?php
/**
 * Simple event observer implementation
 *
 * @author Fabrizio Branca
 * @since 2011-11-24
 */
class Menta_Events {

	/**
	 * @var array
	 */
	protected static $observers = array();

	/**
	 * Add observer
	 *
	 * @static
	 * @throws InvalidArgumentException
	 * @param string $eventKey
	 * @param callback $observer
	 * @return void
	 */
	public static function addObserver($eventKey, $observer) {
		if (!is_string($eventKey) || empty($eventKey)) {
			throw new InvalidArgumentException("Parameter 'eventKey' must be a non empty string");
		}
		if (!is_callable($observer)) {
			throw new InvalidArgumentException("Parameter 'observer' must be a valid callback");
		}
		if (!isset(self::$observers[$eventKey])) {
			self::$observers[$eventKey] = array();
		}
		self::$observers[$eventKey][]= $observer;
	}

	/**
	 * Dispatch event
	 *
	 * @static
	 * @throws InvalidArgumentException
	 * @param string $eventKey
	 * @param array $parameters
	 * @return void
	 */
	public static function dispatchEvent($eventKey, array $parameters=array()) {
		if (!is_string($eventKey) || empty($eventKey)) {
			throw new InvalidArgumentException("Parameter 'eventKey' must be a non empty string");
		}
		if (isset(self::$observers[$eventKey])) {
			foreach (self::$observers[$eventKey] as $observer) { /* @var $observer callback */
				call_user_func_array($observer, $parameters);
			}
		}
	}

}
