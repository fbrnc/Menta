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
	 * @param $component
	 * @return Menta_Interface_Component
	 */
	public static function get($component) {
		if (empty($component) || !is_string($component)) {
			throw new InvalidArgumentException('Parameter must be a classname');
		}
		if (!isset(self::$components[$component])) {
			if (!class_exists($component)) {
				throw new Exception('Could not find component '.$component);
			}
			self::$components[$component] = new $component();
			if (!self::$components[$component] instanceof Menta_Interface_Component) {
				throw new Exception("Component '$component' does not implement interface 'Menta_Interfaces_Component'");
			}
			Menta_Events::dispatchEvent('after_component_create', array('component' => self::$components[$component]));
			Menta_Events::dispatchEvent('after_component_create_' . $component, array('component' => self::$components[$component]));
		}
		return self::$components[$component];
	}

}
