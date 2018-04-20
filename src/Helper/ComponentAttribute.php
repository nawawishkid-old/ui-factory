<?php

namespace UIFactory\Helper;

trait BaseAttribute
{
	/**
	 * Add HTML attributes to component
	 *
	 * @param array $attributes Array of HTML attributes e.g. ['class' => 'my-class']
	 * @return Base
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
	 * @uses Base::getSingleInlineAttribute() to get one inline attribute
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
	 * @uses Base::getAttributeValue() to get only attribute value, not key-value pair
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
	 * @uses Base::getCSSRuleString() e.g. 'color:red;'
	 *
	 * @param string $name Name of attribute
	 * @return string Attribute value or empty string
	 */
	protected function getAttributeValue(string $name)
	{
		return $name === 'style' 
					? $this->getCSSRuleString() 
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
		if (! isset($this->attributes['style'])) {
			return '';
		}

		$css = '';

		foreach ( $this->attributes['style'] as $prop => $value) {
			$css .= $prop . ':' . $value . ';';
		}

		return $css;
	}
}