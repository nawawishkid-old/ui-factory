<?php

namespace UIFactory;

use Exception;

class FactoryBuilder
{	
	public static $instance = null;
	protected static $themes = [];
	protected static $factories = [];
	protected static $requiredBases = [];
	protected static $configs = [
		'debug' => false,
		'PROP_VALIDATION' => true
	];

	private function __construct() {}

	private function __clone() {}
	private function __wakeup() {}

	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function registerFactory(string $name, $factory)
	{
		self::qualifyFactory($name, $factory);

		self::registerProperty('factories', $name, $factory);
	}

	public static function registerTheme(string $name, $theme)
	{
		self::registerProperty('themes', $name, $theme);
	}

	public static function getFactory(string $name)
	{
		$x = self::getProperty('factories', $name);
		return new $x;
	}

	public static function getTheme(string $name)
	{
		$x = self::getProperty('themes', $name);
		return new $x;
	}

	public static function requires(array $component_name)
	{
		self::$requiredBases = $component_name;
	}

	protected static function qualifyFactory(string $name, $factory)
	{
		foreach (self::$requiredBases as $value) {
			try {
				if (! method_exists($factory, $value)) {
					throw new Exception();
				}
			} catch (Exception $e) {
				if (! self::$config('debug')) {
					return;
				}
				
				echo "<b>Error from FactoryBuilder:</b> '<b>$name</b>' is unqualified property. Method `<b>$value</b>` is required.<br>";
			}
		}

		return true;
	}

	public static function config($name, $value = null)
	{
		if (is_null($value)) {
			return self::$configs[$name];
		}

		self::$configs[$name] = $value;
	}

	/************************************
	 ************* HELPER ***************
	 ************************************/
	protected static function registerProperty(string $type, $name, $value)
	{
		self::${$type}[$name] = $value;
	}

	protected static function getProperty(string $type, string $name)
	{
		if (! isset(self::${$type}[$name])) {
			throw new Exception("$name is unqualified property.");
			
		}

		return self::${$type}[$name];
	}

	/**
	 * Get property name form class name or class instance
	 *
	 * @param string|class $property
	 */
	protected static function getPropertyNameFromClass($property)
	{
		$string = is_string($property) ? $property : get_class($property);

		preg_match('/\w+$/', $string, $matches);

		return strtolower($matches[0]);
	}
}