<?php

namespace UIFactory;

class FactoryBuilder
{	
	public static $instance = null;
	protected static $themes = [];
	protected static $factories = [];

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

	public function registerFactory(string $class)
	{
		self::registerProperty('factories', self::getPropertyNameFromClass($class), $class);
	}

	public function registerTheme(string $class)
	{
		self::registerProperty('themes', self::getPropertyNameFromClass($class), $class);
	}

	protected static function registerProperty(string $type, $name, $value)
	{
		if (isset(var))
		self::${$type}[$name] = $value;
	}

	public function getFactory(string $name)
	{
		return new self::getProperty($name);
	}

	public function getFactory(string $name)
	{
		return new self::getProperty($name);
	}

	protected static function getProperty(string $type, string $name)
	{
		return self::${$type}[$name];
	}

	protected static function getPropertyNameFromClass($factory)
	{
		$string = is_string($factory) ? $factory : get_class($factory);

		preg_match('/\w+$/', $string, $matches);

		return strtolower($matches[0]);
	}
}