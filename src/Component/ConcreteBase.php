<?php

namespace UIFactory\Component;

class ConcreteBase extends Base
{
	protected $markupCallbacks = [];

	protected function markup() {}

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

	public function make()
	{
		$props = (object) $this->props;
		$html = $this->markup($props);

		foreach ($this->markupCallbacks as $key => $value) {
			$html .= call_user_func_array($callback, [$props])
		}

		$this->html = $html;
	}

	public function render()
	{

	}
}