<?php

namespace UIFactory\Component;

use Exception;
use UIFactory\Helper\ComponentDirector;
use UIFactory\Helper\ComponentProperty;

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
	 * @var array
	 */
	private static $availableRestrictedPropRules = [
		'type', 'in', 'not_in'
	];

	protected $configs = [
		'PROP_VALIDATION' => false
	];

	/**
	 * HTML markup of this component
	 *
	 * @return string HTML markup
	 */
	abstract protected function markup() : string;

	/**
	 * Set theme and echo this component if requires
	 *
	 * @uses Base::print() to echo component
	 *
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

	public function get($amount = 1)
	{
		return $this->getHTML($amount);
	}

	/**
	 * Echo markup
	 *
	 * @uses Base::markup() to get component HTML markup
	 *
	 * @return void
	 */
	public function print($amount = 1)
	{
		echo $this->getHTML($amount);
	}

	public function getHTML($amount = 1)
	{
		if (! isset($this->html) || empty($this->html)) {
			$this->make($amount);
		}

		return $this->html;
	}

	/**
	 * Return component markup
	 *
	 * @uses Base::markup() to get component HTML markup
	 *
	 * @return string HTML markup
	 */
	public function make($amount = 1)
	{
		$this->checkRequiredProps();

		if ($amount > 1) {
			$this->makeMultiple($amount);
		} elseif ($amount === 1) {
			$this->html = $this->markup();
		}

		return $this;
	}

	protected function makeMultiple(int $amount)
	{
		$markups = '';

		foreach (range(1, $amount) as $index) {
			$markups .= $this->markup();
		}

		$this->html = $markups;
	}

	/**
	 * Base configurations
	 *
	 * @param
	 * @param
	 * @return
	 */
	public function config(string $name, $value = null)
	{
		if (! isset($this->configs[$name])) {
			return null;
		}

		if (is_null($value)) {
			return $this->configs[$name];
		}

		$this->configs[$name] = $value;
		return $this;
	}

	public function condition(callable $callback)
	{
		call_user_func_array($callback, [$this]);
		return $this;
	}
}