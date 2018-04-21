<?php

namespace UIFactory;

use Exception;

/**
 * Base class for component class to extends
 */
abstract class Component
{
	/**
	 * @var array $props Array of default properties for building this component.
	 */
	protected $props = [];

	/**
	 * @var array $prorequiredPropsps Array of required properties for building this component.
	 */
	protected $requiredProps = [];

	/**
	 * @var array $requiredValidationProps Array of expected client's property value. Use to restrict client's property value
	 */
	protected $requiredValidationProps = [];

	/**
	 * @var array $availablePropValidationRules Different type of prop validation rules. Use in Component::getPropValidationRuleName()
	 */
	private static $availablePropValidationRules = [
		'type', 'in', 'not_in'
	];

	protected $markupCallbacks = [];

	protected $html = '';

	/**
	 * @var array $configs Array of component's configuration
	 */
	protected static $configs = [
		'PROP_VALIDATION' => false
	];

	/**
	 * Abstract function for returning HTML markup of this component
	 *
	 * @param \stdClass $props Component's property
	 * @return string HTML markup
	 */
	abstract protected function markup($props) : string;

	/**
	 * Set theme and echo this component if requires
	 *
	 * @uses Component::prop() to set component's properties
	 * @uses Component::print() to echo component
	 * @param mixed $echo Echo the component immediately?
	 * @return void
	 */
	public function __construct(array $props = [], $echo = true)
	{
		$this->markupCallbacks[] = [$this, 'markup'];

		if (! empty($props)) {
			$this->editProps($props);
		}

		if ($echo) {
			$this->render();
		}

		// if (! empty($props)) {
		// 	$this->prop($props);
		// }

		// if ($echo) {
		// 	$this->print($echo);
		// }
	}

	public function __get($name)
	{
		return isset($this->props[$name]) ? $this->props[$name] : null;
	}

	/**
	 * Return component markup
	 *
	 * @api
	 * @uses Component::getHTML() to actually get component's markup
	 * @param int $amount Number of component's duplication
	 * @return string HTML markup of this component
	 */
	public function get()
	{
		$this->make();
		return $this->html;
	}

	/**
	 * Echo component's markup
	 *
	 * @api
	 * @uses Component::markup() to get component HTML markup
	 * @return void
	 */
	public function render()
	{
		$this->make();
		echo $this->html;
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
	 * @return Component
	 */
	public function condition(callable $callback)
	{
		call_user_func_array($callback, [$this]);
		return $this;
	}
	
	/**
	 * Get/set component's properties in single method
	 *
	 * @api
	 * @uses Component::setProp() to set prop
	 * @uses Component::getProp() to get prop
	 * @param string|array $prop Name or array of name-value pairs of properties. string is getting, array is setting
	 * @param mixed $default Value to return if given prop not exists
	 * @return mixed
	 */
	// public function prop($prop, $default = '')
	// {
	// 	if (is_array($prop)) {
	// 		$this->setProp($prop, $default);
	// 		return $this;
	// 	}

	// 	return $this->getProp($prop, $default);
	// }

	public function editProps(array $props)
	{
		// var_dump($props['footerContent']);
		foreach ($props as $key => $value) {
			if (! $this->config('PROP_VALIDATION')) {
				if (isset($this->props[$key]) || in_array($key, array_values($this->requiredProps))) {
					$this->props[$key] = $value;
				}
			}

			if (isset($this->props[$key]) || in_array($key, array_values($this->requiredProps))) {
				$this->validateRequiredValidationProp($key, $value);
				$this->props[$key] = $value;
			}
		}

		return $this;
	}

	/**
	 * Get component's markup from Component::$html
	 *
	 * @uses Component::make() to assign markup to Component::$html if it has not assigned yet.
	 * @param int $amount @see Component::makeMultiple()
	 * @return string HTML markup of this component
	 */
	// protected function getHTML(int $amount = 1)
	// {
	// 	if (! isset($this->html) || empty($this->html)) {
	// 		$this->make($amount);
	// 	}

	// 	return $this->html;
	// }

	/**
	 * Assign component's markup to Component::$html
	 *
	 * @uses Component::checkRequiredProps() to validate client-given property
	 * @uses Component::makeMultiple() to assign multiple component's duplication to Component::$html
	 * @uses Component::markup() to get component HTML markup
	 * @param int $amount @see Component::makeMultiple()
	 * @return string HTML markup
	 */
	protected function make($amount = 1)
	{
		$this->checkRequiredProps();

		$html = '';

		foreach ($this->markupCallbacks as $callback) {
			$html .= call_user_func_array($callback, [(object) $this->props]);
		}

		$this->html = $html;

		return $this;

		// if ($amount > 1) {
		// 	$this->makeMultiple($amount);
		// } elseif ($amount === 1) {
		// 	$this->html = $this->markup();
		// }

		// return $this;
	}

	/**
	 * Assign multiple component's duplication to Component::$html
	 *
	 * @uses Component::markup() to get component's markup
	 * @param int $amount Number of component's duplication
	 * @return
	 */
	// protected function makeMultiple(int $amount)
	// {
	// 	$markups = '';

	// 	foreach (range(1, $amount) as $index) {
	// 		$markups .= $this->markup();
	// 	}

	// 	$this->html = $markups;
	// }

	/**
	 * Get component's property
	 *
	 * @param string $prop_name Name of property
	 * @param mixed $default Value to return if given prop not exists
	 * @return mixed Component's property
	 */
	// protected function getProp(string $prop_name, $default = '')
	// {
	// 	return isset($this->props[$prop_name]) 
	// 				? $this->props[$prop_name] 
	// 				: $default;
	// }

	/**
	 * Set component's property
	 *
	 * @uses Component::config()
	 * @param array $prop_array Array of name-value pairs of property
	 * @param mixed $default Value to return if given prop not exists
	 * @return Component
	 */
	// protected function setProp(array $prop_array)
	// {
	// 	if (! $this->config('PROP_VALIDATION')) {
	// 		foreach ($prop_array as $name => $value) {
	// 			$this->props[$name] = $value;
	// 		}

	// 		return $this;
	// 	}

	// 	foreach ($prop_array as $name => $value) {
	// 		$this->validateRequiredValidationProp($name, $value);
	// 		$this->props[$name] = $value;
	// 	}

	// 	return $this;
	// }

	/**
	 * Check whether client has given all required properties. If not, throw an error
	 *
	 * @uses Component::getComponentNameFromClass()
	 * @return void
	 */
	protected function checkRequiredProps()
	{
		$prop_name = array_keys($this->props);

		foreach ($this->requiredProps as $opt) {
			if (! in_array($opt, $prop_name)) {
				$name = $this->getComponentNameFromClass($this);

				throw new Exception("Component '$name' requires prop '$opt'.");
				
			}
		}
	}

	/**
	 * Qualification of component's restricted properties. In other words, client-given property validation.
	 *
	 * @uses Component::getPropValidationRuleName() to get prop validation rule
	 * @uses Component::requiredValidationPropTypeIs() to check type of client-given prop
	 * @uses Component::requiredValidationPropIsIn() to check if client-given prop is one of specific value
	 * @uses Component::requiredValidationPropIsNotIn() to check if client-given prop is not one of specific value
	 * @param string $name Key of UIFactory\Components\Component::$requiredValidationProps to get validation rule
	 * @param mixed $value Value of client-given prop to validate
	 * @return void
	 */
	protected function validateRequiredValidationProp(string $name, $value)
	{
		if (! isset($this->requiredValidationProps[$name])) {
			return;
		}

		$rule = $this->requiredValidationProps[$name];
		$rule_name = $rule[0];
		$rule_value = $rule[1];

		if (! $this->propValidationRuleNameIsValid($rule_name)) {
			throw new Exception("'$rule_name' is not a valid prop validation rule.");
		}

		switch ($rule_name) {
			case 'type':
				$valid = $this->requiredValidationPropTypeIs($rule_value, $value);
				break;
			case 'in':
				$valid = $this->requiredValidationPropIsIn($rule_value, $value);
				break;
			case 'not_in':
				$valid = $this->requiredValidationPropIsNotIn($rule_value, $value);
				break;
		}

		if ($valid !== true) {
			throw new Exception($valid);
			
		}
	}

	/**
	 * Validate client-given prop by type of prop
	 *
	 * @param string $rule_value Validation rule from UIFactory\Components\Component::$requiredValidationProps
	 * @param mixed $value Client-given value
	 * @return bool|string True, if client-given prop is valid. Otherwise, error string to use in exception
	 */
	protected function requiredValidationPropTypeIs($rule_value, $value)
	{
		$type = ['string', 'array', 'bool', 'int', 'float', 'callable'];

		if (is_string($rule_value)) {
			$valid = gettype($value) === $rule_value;
		} elseif (is_array($rule_value)) {
			$valid = in_array(gettype($value), $rule_value);
		}

		// $valid = in_array($rule_value, $type)
		// 			? call_user_func_array('is_' . $rule_value, [$value])
		// 			: is_a($value, $rule_value);

		if (! $valid) {
			return "Type of given prop must be $rule_value, " . gettype($value) . " given.";
		}

		return true;
	}

	/**
	 * Validate that client-given prop must be one of specific value specified in inherited class of UIFactory\Components\Component
	 *
	 * @param string $rule_value Validation rule from UIFactory\Components\Component::$requiredValidationProps
	 * @param mixed $value Client-given value
	 * @return bool|string True, if client-given prop is valid. Otherwise, error string to use in exception
	 */
	protected function requiredValidationPropIsIn(array $rule_value, $value)
	{
		if (! in_array($value, $rule_value)) {
			return "Given prop must be one of " . implode(', ', $rule_value);
			
		}

		return true;
	}

	/**
	 * Validate that client-given prop must NOT be one of specific value specified in inherited class of UIFactory\Components\Component
	 *
	 * @param string $rule_value Validation rule from UIFactory\Components\Component::$requiredValidationProps
	 * @param mixed $value Client-given value
	 * @return bool|string True, if client-given prop is valid. Otherwise, error string to use in exception
	 */
	protected function requiredValidationPropIsNotIn(array $rule_value, $value)
	{
		if ($this->requiredValidationPropIsIn($rule_value, $value) === true) {
			return "Given prop must not be one of " . implode(', ', $rule_value);
		}

		return true;
	}

	/**
	 * Get prop validation rule
	 *
	 * @param string $name Key of UIFactory\Components\Component::$requiredValidationProps to get validation rule
	 * @return string|array Validation rule from UIFactory\Components\Component::$requiredValidationProps
	 */
	protected function getPropValidationRuleName(string $name)
	{
		$rule_name = $this->requiredValidationProps[$name][0];
		// $rule = is_string($raw_rule)
		// 			? 'type'
		// 			: (is_array($raw_rule) ? $raw_rule[0] : null);

		if (! in_array($rule_name, self::$availablePropValidationRules)) {
			throw new Exception("'$rule_name' is not a valid prop validation rule.");
			
		}

		return $rule_name;
	}

	protected function propValidationRuleNameIsValid(string $name)
	{
		if (! in_array($name, self::$availablePropValidationRules)) {
			return false;
		}

		return true;
	}

	protected function getComponentNameFromClass($component)
	{
		$string = is_string($component) ? $component : get_class($component);

		preg_match('/\w+$/', $string, $matches);

		return strtolower($matches[0]);
	}
}