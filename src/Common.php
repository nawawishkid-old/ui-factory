<?php

namespace UIFactory\Component;

use UIFactory\Theme;
use UIFactory\Helper\ComponentDirector;

abstract class Common
{
	use ComponentDirector;

	/**
	 * @var array HTML attributes
	 */
	protected $attributes = [
		'style' => [],
		'class' => ''
	];

	protected $options = [];
	
	/**
	 * @var string Atom's inner HTML
	 */
	protected $content = '';

	/**
	 * HTML markup of this component
	 *
	 * @return string HTML markup
	 */
	abstract protected function markup() : string;

	/**
	 * Set theme and echo this component if requires
	 *
	 * @uses Common::print() to echo component
	 *
	 * @param Theme $theme Theme instance
	 * @param mixed $echo Echo the component immediately?
	 * @return void
	 */
	public function __construct(Theme $theme, $echo = true)
	{
		$this->theme = $theme;

		if ($echo) {
			$this->print();
		}
	}

	/**
	 * Return component markup
	 *
	 * @uses Common::markup() to get component HTML markup
	 *
	 * @return string HTML markup
	 */
	public function make()
	{
		return $this->markup();
	}

	/**
	 * Echo markup
	 *
	 * @uses Common::markup() to get component HTML markup
	 *
	 * @return void
	 */
	public function print()
	{
		echo $this->markup();
	}

	public function getTheme()
	{
		return $this->theme;
	}

	/**
	 * Set component's inner HTML
	 *
	 * @param string $content Content to set
	 * @return Common
	 */
	public function content(string $content)
	{
		$this->content = $content;
		return $this;
	}

	/**
	 * Append content to component's inner HTML by string concatenation
	 *
	 * @param string $content Content to append
	 * @return Common
	 */
	public function appendContent(string $content)
	{
		$this->content = $this->content . $content;
		return $this;
	}

	/**
	 * Prepend component's inner HTML by string concatenation
	 *
	 * @param string $content Content to prepend
	 * @return Common
	 */
	public function prependContent(string $content)
	{
		$this->content = $content . $this->content;
		return $this;
	}

	public function option(string $name, $value = null)
	{
		if (is_null($value)) {
			return $this->options[$name];
		}

		$this->options[$name] = $value;
		return $this;
	}

	/**
	 * Add HTML attributes to component
	 *
	 * @param array $attributes Array of HTML attributes e.g. ['class' => 'my-class']
	 * @return Common
	 */
	public function addAttributes(array $attributes)
	{
		if (isset($attributes['style'])) {
			$attributes['style'] = $this->mergeStyleAttributes($attributes['style']);
		}

		if (isset($attributes['class'])) {
			$attributes['class'] = $this->concatClassAttributes($attributes['class']);
		}

		$this->attributes = array_merge($this->attributes, $attributes);

		return $this;
	}

	protected function mergeStyleAttributes(array $style)
	{
		$default_style = isset($this->attributes['style']) ? $this->attributes['style'] : [];
		$default_style = is_array($default_style) ? $default_style : [];

		return array_merge($default_style, $style);
	}

	protected function concatClassAttributes(string $class)
	{
		$default_class = isset($this->attributes['class']) ? $this->attributes['class'] : '';
		return $default_class . ' ' . $class;
	}

	/**
	 * Get inline HTML attribute key-value e.g. style="background:red;"
	 *
	 * @uses Common::getSingleInlineAttribute() to get one inline attribute
	 *
	 * @param string|array|null $arg (Array of) attribute name, or null to get all attributes
	 * @return string HTML attributes or empty string
	 */
	protected function getInlineAttribute($arg = null)
	{
		if (is_string($arg)) {
			return $this->getSingleInlineAttribute($arg);
		}

		$attributes = '';

		if (is_array($arg)) {
			foreach ($arg as $name) {
				$attributes .= $this->getSingleInlineAttribute($name);
			}

			return $attributes;
		}

		foreach ( $this->attributes as $name => $value) {
			$attributes .= $this->getSingleInlineAttribute($name);
		}

		return $attributes;
	}

	/**
	 * Get inline HTML attribute key-value e.g. style="color:red;"
	 *
	 * @uses Common::getAttributeValue() to get only attribute value, not key-value pair
	 *
	 * @param string $name Name of attribute
	 * @return string HTML attributes or empty
	 */
	private function getSingleInlineAttribute(string $name)
	{
		$value = $this->getAttributeValue($name);
		return empty($value) ? '' : $name . '="' . $value . '" ';
	}

	/**
	 * Get component's HTML attribute
	 *
	 * @uses Common::getCSSRuleString() e.g. 'color:red;'
	 *
	 * @param string $name Name of attribute
	 * @return string Attribute value or empty string
	 */
	private function getAttributeValue(string $name)
	{
		return $name === 'style' ? $this->getCSSRuleString() 
								 : (isset($this->attributes[$name]) ? $this->attributes[$name] : '');
	}

	/**
	 * Get CSS rule string e.g. 'color:red;'
	 * Can be used in $this->markup()
	 *
	 * @return string CSS rule
	 */
	private function getCSSRuleString()
	{
		$css = '';

		foreach ( $this->attributes['style'] as $prop => $value) {
			$css .= $prop . ':' . $value . ';';
		}

		return $css;
	}
}