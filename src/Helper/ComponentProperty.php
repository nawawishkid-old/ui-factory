<?php

namespace UIFactory\Helper;

use Exception;

/**
 * A helper trait for managing component's properties. Use in UIFactory\Component\Base class
 * When refers to 'ComponentProperty', it means the class which uses this trait
 */
trait ComponentProperty
{
	/**
	 * API for get/set component's properties in single method
	 *
	 * @api
	 * @uses ComponentProperty::setProp() to set prop
	 * @uses ComponentProperty::getProp() to get prop
	 * @param string|array $prop Name or array of name-value pairs of properties. string is getting, array is setting
	 * @param mixed $default Value to return if given prop not exists
	 * @return mixed
	 */
	public function prop($prop, $default = '')
	{
		if (is_array($prop)) {
			$this->setProp($prop, $default);
			return $this;
		}

		return $this->getProp($prop, $default);
	}

	/**
	 * Get component's property
	 *
	 * @param string $prop_name Name of property
	 * @param mixed $default Value to return if given prop not exists
	 * @return mixed Component's property
	 */
	protected function getProp(string $prop_name, $default = '')
	{
		return isset($this->props[$prop_name]) 
					? $this->props[$prop_name] 
					: $default;
	}

	/**
	 * Set component's property
	 *
	 * @uses ComponentProperty::config()
	 * @param array $prop_array Array of name-value pairs of property
	 * @param mixed $default Value to return if given prop not exists
	 * @return ComponentProperty
	 */
	protected function setProp(array $prop_array)
	{
		if (! $this->config('PROP_VALIDATION')) {
			foreach ($prop_array as $name => $value) {
				$this->props[$name] = $value;
			}

			return $this;
		}

		foreach ($prop_array as $name => $value) {
			$this->checkRestrictedProps($name, $value);
			$this->props[$name] = $value;
		}

		return $this;
	}

	/**
	 * Check whether client has given all required properties. If not, throw an error
	 *
	 * @uses ComponentProperty::getComponentNameFromClass()
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
	 * @uses ComponentProperty::getRestrictedPropRule() to get prop validation rule
	 * @uses ComponentProperty::restrictedPropTypeIs() to check type of client-given prop
	 * @uses ComponentProperty::restrictedPropIsIn() to check if client-given prop is one of specific value
	 * @uses ComponentProperty::restrictedPropIsNotIn() to check if client-given prop is not one of specific value
	 * @param string $name Key of UIFactory\Component\Base::$restrictedProps to get validation rule
	 * @param mixed $value Value of client-given prop to validate
	 * @return void
	 */
	protected function checkRestrictedProps(string $name, $value)
	{
		if (! isset($this->restrictedProps[$name])) {
			return;
		}

		$rule_value = $this->restrictedProps[$name];

		switch ($this->getRestrictedPropRule($name)) {
			case 'type':
				$valid = $this->restrictedPropTypeIs($rule_value, $value);
				break;
			case 'in':
				$valid = $this->restrictedPropIsIn($rule_value[1], $value);
				break;
			case 'not_in':
				$valid = $this->restrictedPropIsNotIn($rule_value[1], $value);
				break;
		}

		if ($valid !== true) {
			throw new Exception($valid);
			
		}
	}

	/**
	 * Validate client-given prop by type of prop
	 *
	 * @param string $rule_value Validation rule from UIFactory\Component\Base::$restrictedProps
	 * @param mixed $value Client-given value
	 * @return bool|string True, if client-given prop is valid. Otherwise, error string to use in exception
	 */
	protected function restrictedPropTypeIs(string $rule_value, $value)
	{
		$type = ['string', 'array', 'bool', 'int', 'float', 'callable'];

		$valid = in_array($rule_value, $type)
					? call_user_func_array('is_' . $rule_value, [$value])
					: is_a($value, $rule_value);

		if (! $valid) {
			return "RestrictedProp's type must be $rule_value, " . gettype($value) . " given.";
		}

		return true;
	}

	/**
	 * Validate that client-given prop must be one of specific value specified in inherited class of UIFactory\Component\Base
	 *
	 * @param string $rule_value Validation rule from UIFactory\Component\Base::$restrictedProps
	 * @param mixed $value Client-given value
	 * @return bool|string True, if client-given prop is valid. Otherwise, error string to use in exception
	 */
	protected function restrictedPropIsIn(array $rule_value, $value)
	{
		if (! in_array($value, $rule_value)) {
			return "RestrictedProp must be one of " . implode(', ', $rule_value);
			
		}

		return true;
	}

	/**
	 * Validate that client-given prop must NOT be one of specific value specified in inherited class of UIFactory\Component\Base
	 *
	 * @param string $rule_value Validation rule from UIFactory\Component\Base::$restrictedProps
	 * @param mixed $value Client-given value
	 * @return bool|string True, if client-given prop is valid. Otherwise, error string to use in exception
	 */
	protected function restrictedPropIsNotIn(array $rule_value, $value)
	{
		if ($this->restrictedPropIsIn($rule_value, $value) === true) {
			return "RestrictedProp must not be one of " . implode(', ', $rule_value);
		}

		return true;
	}

	/**
	 * Get prop validation rule
	 *
	 * @param string $name Key of UIFactory\Component\Base::$restrictedProps to get validation rule
	 * @return string|array Validation rule from UIFactory\Component\Base::$restrictedProps
	 */
	protected function getRestrictedPropRule(string $name)
	{
		$raw_rule = $this->restrictedProps[$name];
		$rule = is_string($raw_rule)
					? 'type'
					: (is_array($raw_rule) ? $raw_rule[0] : null);

		if (! in_array($rule, self::$availableRestrictedPropRules)) {
			throw new Exception("'$rule' is not a valid restrictedProp rule.");
			
		}

		return $rule;
	}
}