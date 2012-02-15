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
	
	protected static $rewrites = array();

	/**
	 * Set rewrite
	 *
	 * @static
	 * @param string $originalClassname
	 * @param string $targetClassname
	 * @throws Exception|InvalidArgumentException
	 * @return void
	 */
	public static function addRewrite($originalClassname, $targetClassname) {
		if (empty($originalClassname) || !is_string($originalClassname)) {
			throw new InvalidArgumentException('Invalid originalClassname');
		}
		if (empty($targetClassname) || !is_string($targetClassname)) {
			throw new InvalidArgumentException('Invalid targetClassname');
		}
		if (isset(self::$rewrites[$originalClassname])) {
			throw new Exception("Rewrite for '$originalClassname'' already exists.");
		}
		self::$rewrites[$originalClassname] = $targetClassname;
	}

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

		$originalComponentClass = $component;

		// resolve rewrite
		if (isset(self::$rewrites[$component])) {
			$component = self::$rewrites[$component];
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

			if ($originalComponentClass != $component && !self::$components[$component][$instanceKey] instanceof $originalComponentClass) {
				throw new Exception("Rewrite '$component' does not extend original class '$originalComponentClass'");
			}

			// fire events
			$eventParamaters = array(
				'component' => self::$components[$component][$instanceKey],
				'instanceKey' => $instanceKey
			);
			Menta_Events::dispatchEvent('after_component_create', $eventParamaters);
			Menta_Events::dispatchEvent('after_component_create_' . $component, $eventParamaters);
		}
		return self::$components[$component][$instanceKey];
	}

}
