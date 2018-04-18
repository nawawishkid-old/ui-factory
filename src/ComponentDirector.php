<?php

namespace UIFactory\Helper;

use UIFactory\Component\Common;

trait ComponentDirector
{
	protected function initComponentList(string $type)
	{
		$type .= 's';

		foreach ($this->{$type} as $key => $class) {
			$this->{$type}[$this->getComponentNameFromClass($class)] = $class;
			unset($this->{$type}[$key]);
		}
	}

	protected function addComponent(string $type, Common $component)
	{
		$this->{$type . 's'}[$this->getComponentNameFromClass($component)] = $component;
	}

	protected function getComponent(string $type, string $name, $echo)
	{
		return new $this->{$type . 's'}[$name]($this->theme, $echo);
	}

	protected function editComponent(string $type, string $name, callable $callback)
	{
		$type .= 's';
		$component = new $this->{$type}[$name]($this->theme, false);
		$this->{$type}[$name] = call_user_func_array($callback, [$component]);
	}

	protected function getComponentNameFromClass($component)
	{
		$string = is_string($component) ? $component : get_class($component);

		preg_match('/\w+$/', $string, $matches);

		return strtolower($matches[0]);
	}
}