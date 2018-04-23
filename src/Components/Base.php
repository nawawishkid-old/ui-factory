<?php

namespace UIFactory\Components;

use UIFactory\Component;

class Base extends Component
{
	protected $helpers = [];

	protected function markup($props) : string {
		return '';
	}

	public function addMarkup(callable $callback)
	{
		$this->markupCallbacks[] = $callback;
		return $this;
	}

	public function addProps(array $props)
	{
		$this->props = array_merge($props);
		return $this;
	}

	public function addRequiredValidationProps(array $prop_rules)
	{
		$this->requiredValidationProps = $prop_rules;
		return $this;
	}

	public function addHelper(string $name, callable $callback)
	{
		$this->helpers[$name] = $callback;
		return $this;
	}

	public function helper(string $name, array $params)
	{
		return call_user_func_array($this->{$name}, $params);
	}
}