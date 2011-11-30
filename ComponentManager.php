<?php
/**
 * ComponentManager
 *
 * @author Fabrizio Branca
 * @since 2011-11-24
 */
class Menta_ComponentManager {

	/**
	 * @var array
	 */
	protected static $components = array();

	/**
	 * Get component
	 *
	 * @static
	 * @throws Exception|InvalidArgumentException
	 * @param string $component
	 * @param string $instanceKey
	 * @return Menta_Interface_Component
	 */
	public static function get($component, $instanceKey='default') {
		if (empty($component) || !is_string($component)) {
			throw new InvalidArgumentException('Parameter "component" must be a classname');
		}
		if (empty($instanceKey) || !is_string($instanceKey)) {
			throw new InvalidArgumentException('Parameter "instanceKey" must be a non empty string');
		}
		if (!isset(self::$components[$component])) {
			self::$components[$component] = array();
		}
		if (!isset(self::$components[$component][$instanceKey])) {
			if (!class_exists($component)) {
				throw new Exception('Could not find component '.$component);
			}
			self::$components[$component][$instanceKey] = new $component();
			if (!self::$components[$component][$instanceKey] instanceof Menta_Interface_Component) {
				throw new Exception("Component '$component' does not implement interface 'Menta_Interfaces_Component'");
			}

			// fire events
			$eventParamaters = array(
				'component' => self::$components[$component],
				'instanceKey' => $instanceKey
			);
			Menta_Events::dispatchEvent('after_component_create', $eventParamaters);
			Menta_Events::dispatchEvent('after_component_create_' . $component, $eventParamaters);
		}
		return self::$components[$component][$instanceKey];
	}

}
