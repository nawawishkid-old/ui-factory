<?php

namespace UIFactory\Helper;

use Exception;
use UIFactory\FactoryBuilder as FB;


trait ComponentProperty
{

	public function prop($prop, $default = '')
	{
		if (is_array($prop)) {
			if (! $this->config('PROP_VALIDATION')) {
				foreach ($prop as $name => $value) {
					$this->props[$name] = $value;
				}

				return $this;
			}

			foreach ($prop as $name => $value) {
				$this->checkRestrictedProps($name, $value);
				$this->props[$name] = $value;
			}
			// $this->props = array_merge($this->props, $prop);

			return $this;
		}

		return isset($this->props[$prop]) 
					? $this->props[$prop] 
					: $default;
	}

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
				$valid = $this->restrictedPropIsIn($rule_value, $value);
				break;
			case 'not_in':
				$valid = $this->restrictedPropIsNotIn($rule_value, $value);
				break;
		}

		if ($valid !== true) {
			throw new Exception($valid);
			
		}
	}

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

	protected function restrictedPropIsIn(array $rule_value, $value)
	{
		$array = $rule_value[1];
		if (! in_array($value, $array)) {
			return "RestrictedProp must be one of " . implode(', ', $array);
			
		}

		return true;
	}

	protected function restrictedPropIsNotIn(array $rule_value, $value)
	{
		if ($this->restrictedPropIsIn($rule_value, $value) === true) {
			return "RestrictedProp must not be one of " . implode(', ', $rule_value[1]);
		}

		return true;
	}

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