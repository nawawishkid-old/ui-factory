<?php

namespace UIFactory\DOM

class DOMElement
{
	/**
	 * @var array Array of HTML style attribute values
	 */
	protected $styles = [];

	/**
	 * @var array Array of HTML class attribute values
	 */
	protected $classes = [];

	protected $attributes = [];

	/**
	 * @var string Atom's inner HTML
	 */
	protected $content = '';

	public function use($element_name)
	{
		$this->element = $element_name;
		return $this;
	}

	/**
	 *
	 *
	 * @return void
	 */
	public function addAttribute(string $name, string $value)
	{
		if ($name === 'style') {
			$this->addStyle($value)
			return $this;
		} elseif ($name === 'class') {
			$this->addClass($value);
			return $this;
		}

		$this->attributes[$name] = $value;
		return $this;
	}

	/**
	 * Add class attribute value of DOM
	 *
	 * @param string $class
	 * @return $this
	 */
	public function addClass(string $class)
	{
		$this->classes[] = $class;
		return $this;
	}

	/**
	 * Add style attribute value of DOM
	 *
	 * @param string $name CSS property name
	 * @param string $value CSS property value
	 * @return $this
	 */
	public function addStyle(string $name, string $value)
	{
		$this->styles[$name] = $value;
		// $this->addAttribute('style', $name . ':' . $value . ';');
		return $this;
	}

	/**
	 * Set atom's inner HTML
	 *
	 * @param string $content
	 * @return $this
	 */
	public function content(string $content)
	{
		$this->content = $content;
		return $this;
	}

	/**
	 * Get the DOM element's attributes
	 *
	 * @return array All attributes
	 */
	public function getAttributes()
	{
		$this->attributes['style'] = $this->styles;
		$this->attributes['class'] = $this->classes;
		return $this->attributes;
	}
}