<?php

namespace UIFactory\Component;

use Exception;
use UIFactory\Helper\ComponentDirector;
use UIFactory\Helper\ComponentProperty;

/**
 * Base class for component class to extends
 */
abstract class Base
{
	use ComponentDirector;
	use ComponentProperty;

	/**
	 * @var array $props Array of default properties for building this component.
	 */
	protected $props = [];

	/**
	 * @var array $prorequiredPropsps Array of required properties for building this component.
	 */
	protected $requiredProps = [];

	/**
	 * @var array $restrictedProps Array of expected client's property value. Use to restrict client's property value
	 */
	protected $restrictedProps = [];

	/**
	 * @var array $availableRestrictedPropRules Different type of restrictedProp validation. Use in ComponentProperty::getRestrictedPropRule()
	 */
	private static $availableRestrictedPropRules = [
		'type', 'in', 'not_in'
	];

	/**
	 * @var array $configs Array of component's configuration
	 */
	protected static $configs = [
		'PROP_VALIDATION' => false
	];

	/**
	 * Abstract function for creating HTML markup of this component
	 *
	 * @return string HTML markup
	 */
	abstract protected function markup() : string;

	/**
	 * Set theme and echo this component if requires
	 *
	 * @uses Base::prop() to set component's properties
	 * @uses Base::print() to echo component
	 * @param mixed $echo Echo the component immediately?
	 * @return void
	 */
	public function __construct(array $props = [], $echo = 1)
	{
		$this->prop($props);

		if ($echo) {
			$this->print($echo);
		}
	}

	/**
	 * Return component markup
	 *
	 * @api
	 * @uses Base::getHTML() to actually get component's markup
	 * @param int $amount Number of component's duplication
	 * @return string HTML markup of this component
	 */
	public function get($amount = 1)
	{
		return $this->getHTML($amount);
	}

	/**
	 * Echo component's markup
	 *
	 * @api
	 * @uses Base::markup() to get component HTML markup
	 * @return void
	 */
	public function print($amount = 1)
	{
		echo $this->getHTML($amount);
	}

	/**
	 * Get component's markup from Base::$html
	 *
	 * @uses Base::make() to assign markup to Base::$html if it has not assigned yet.
	 * @param int $amount @see Base::makeMultiple()
	 * @return string HTML markup of this component
	 */
	protected function getHTML(int $amount = 1)
	{
		if (! isset($this->html) || empty($this->html)) {
			$this->make($amount);
		}

		return $this->html;
	}

	/**
	 * Assign component's markup to Base::$html
	 *
	 * @uses Base::checkRequiredProps() to validate client-given property
	 * @uses Base::makeMultiple() to assign multiple component's duplication to Base::$html
	 * @uses Base::markup() to get component HTML markup
	 * @param int $amount @see Base::makeMultiple()
	 * @return string HTML markup
	 */
	protected function make($amount = 1)
	{
		$this->checkRequiredProps();

		if ($amount > 1) {
			$this->makeMultiple($amount);
		} elseif ($amount === 1) {
			$this->html = $this->markup();
		}

		return $this;
	}

	/**
	 * Assign multiple component's duplication to Base::$html
	 *
	 * @uses Base::markup() to get component's markup
	 * @param int $amount Number of component's duplication
	 * @return
	 */
	protected function makeMultiple(int $amount)
	{
		$markups = '';

		foreach (range(1, $amount) as $index) {
			$markups .= $this->markup();
		}

		$this->html = $markups;
	}

	/**
	 * Component configurations
	 *
	 * @api
	 * @param string $name Name of configuration you want to get/set
	 * @param mixed $value If set, you get the config value. Otherwise, you set it
	 * @return mixed
	 */
	public function config(string $name, $value = null)
	{
		if (! isset(self::$configs[$name])) {
			return null;
		}

		if (is_null($value)) {
			return self::$configs[$name];
		}

		self::$configs[$name] = $value;
		return $this;
	}

	/**
	 * Make the component behaves differently in specific condition
	 *
	 * @api
	 * @param callable $callback Callback for client to make condition
	 * @return Base
	 */
	public function condition(callable $callback)
	{
		call_user_func_array($callback, [$this]);
		return $this;
	}
}