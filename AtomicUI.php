<?php

namespace UIFactory\Component;

abstract class AtomicUI
{
	protected $styles = [];
	protected $hoverStyles = [];
	protected $classes = [];
	protected $classPrefix = '';
	protected $classPrefixConjunction = '-';

	/**
	 *
	 * @return string HTML markup
	 */
	abstract protected function markup() : string;

	public function make()
	{
		return $this->markup();
	}

	public function print()
	{
		echo $this->markup();
	}

	public function addClass(string $class)
	{
		$this->classes[] = $class;
	}


	/**
	 * Helper methods
	 */
	protected function getClassAttribute()
	{
		return 'class="' . $this->getClassAttributeValue() . '"';
	}

	protected function getStyleAttribute()
	{
		return 'style="' . $this->getStyleAttributeValue() . '"';
	}

	protected function getClassAttributeValue()
	{
		$classes = '';
		$class_prefix_conjunction = empty($this->classPrefix) ? '' : $this->classPrefixConjunction;

		foreach ( $this->classes as $class ) {
			$classes .= $this->classPrefix .$class_prefix_conjunction . $class . ' ';
		}

		return $classes;
	}

	protected function getStyleAttributeValue()
	{
		$css = '';

		foreach ( $this->styles as $prop => $value) {
			$css .= $prop . ':' . $value . ';';
		}

		return $css;
	}
}