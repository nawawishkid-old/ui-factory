<?php

namespace UIFactory\Component;

class ConcreteBase extends Base
{
	protected $markupCallbacks = [];

	protected function markup() : string {}

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

	public function editProps(array $props)
	{
		foreach ($props as $key => $value) {
			if (isset($this->props[$key])) {
				$this->props[$key] = $value;
			}
		}
		return $this;
	}

	public function addRequiredValidationProps(array $prop_rules)
	{
		$this->requiredValidationProps = $prop_rules;
		return $this;
	}

	public function make($amount = 1)
	{
		$props = (object) $this->props;
		$html = ''; // $this->markup($props);

		foreach ($this->markupCallbacks as $callback) {
			$html .= call_user_func_array($callback, [$props]);
		}

		$this->html = $html;

		return $this;
	}

	public function render()
	{
		$this->make();
		echo $this->html;
	}
}