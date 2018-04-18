<?php

namespace UIFactory\Helper;

use UIFactory\Component\Common;

trait ComponentDirector
{
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
		$this->{$type}[$name] = call_user_func_array($callback, [$this->{$type}[$name]]);
	}

	protected function getComponentNameFromClass($component)
	{
		preg_match('/\w+$/', get_class($component), $matches);
		return strtolower($matches[0]);
	}
}