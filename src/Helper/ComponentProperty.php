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
				$this->checkPropTypes($name, $value);
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

	protected function checkPropTypes(string $name, $value)
	{
		if (! isset($this->propTypes[$name])) {
			return;
		}

		$rule_value = $this->propTypes[$name];

		switch ($this->getPropTypeRule($name)) {
			case 'type':
				$valid = $this->propTypeTypeIs($rule_value, $value);
				break;
			case 'in':
				$valid = $this->propTypeIsIn($rule_value, $value);
				break;
			case 'not_in':
				$valid = $this->propTypeIsNotIn($rule_value, $value);
				break;
		}

		if ($valid !== true) {
			throw new Exception($valid);
			
		}
	}

	protected function propTypeTypeIs(string $rule_value, $value)
	{
		$type = ['string', 'array', 'bool', 'int', 'float', 'callable'];

		$valid = in_array($rule_value, $type)
					? call_user_func_array('is_' . $rule_value, [$value])
					: is_a($value, $rule_value);

		if (! $valid) {
			return "PropType's type must be $rule_value, " . gettype($value) . " given.";
		}

		return true;
	}

	protected function propTypeIsIn(array $rule_value, $value)
	{
		$array = $rule_value[1];
		if (! in_array($value, $array)) {
			return "PropType must be one of " . implode(', ', $array);
			
		}

		return true;
	}

	protected function propTypeIsNotIn(array $rule_value, $value)
	{
		if ($this->propTypeIsIn($rule_value, $value) === true) {
			return "PropType must not be one of " . implode(', ', $rule_value[1]);
		}

		return true;
	}

	protected function getPropTypeRule(string $name)
	{
		$raw_rule = $this->propTypes[$name];
		$rule = is_string($raw_rule)
					? 'type'
					: (is_array($raw_rule) ? $raw_rule[0] : null);

		if (! in_array($rule, self::$availablePropTypeRules)) {
			throw new Exception("'$rule' is not a valid propType rule.");
			
		}

		return $rule;
	}
}