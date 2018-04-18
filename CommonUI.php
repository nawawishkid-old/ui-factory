<?php

namespace UIFactory\Component;

abstract class CommonUI
{

	/**
	 * @var array HTML attributes
	 */
	protected $attributes = [
		'style' => [],
		'class' => ''
	];
	
	/**
	 * @var string Atom's inner HTML
	 */
	protected $content = '';

	/**
	 * HTML markup
	 *
	 * @return string HTML markup
	 */
	abstract protected function markup() : string;

	public function __construct(array $theme, bool $echo = true)
	{
		$this->theme = $theme;

		if ($echo) {
			$this->print();
		}
	}

	/**
	 * Use to return markup
	 *
	 * @return string $this->markup()
	 */
	public function make()
	{
		return $this->markup();
	}

	/**
	 * Echo markup
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
	 * ===========================
	 * Public chained methods
	 * ===========================
	 */
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
	 * Append atom's inner HTML
	 *
	 * @param string $content
	 * @return $this
	 */
	public function appendContent(string $content)
	{
		$this->content = $this->content . $content;
		return $this;
	}

	/**
	 * Prepend atom's inner HTML
	 *
	 * @param string $content
	 * @return $this
	 */
	public function prependContent(string $content)
	{
		$this->content = $content . $this->content;
		return $this;
	}


	/**
	 * ===========================
	 * Helper methods
	 * ===========================
	 */
	/**
	 * Add HTML attributes to atom
	 *
	 * @param array $attributes Array of HTML attributes
	 * @return $this
	 */
	public function addAttributes(array $attributes)
	{
		if (isset($attributes['style'])) {
			$attributes['style'] = array_merge($this->attributes['style'], $attributes['style']);
		}

		$this->attributes = array_merge($this->attributes, $attributes);

		return $this;
	}

	/**
	 * Get inline HTML attribute key-value
	 *
	 * @param string|array|null $arg (Array of) attribute name or null to get all attributes
	 * @return string HTML attributes or empty
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
	 * Get inline HTML attribute key-value
	 *
	 * @param string $name Name of attribute
	 * @return string HTML attributes or empty
	 */
	private function getSingleInlineAttribute(string $name)
	{
		$value = $this->getInlineAttributeValue($name);
		return empty($value) ? '' : $name . '="' . $value . '" ';
	}

	/**
	 * Get atom's HTML attribute
	 *
	 * @param string $name Name of attribute
	 * @return string Attribute value
	 */
	private function getInlineAttributeValue(string $name)
	{
		return $name === 'style' ? $this->getStyleString() 
								 : (isset($this->attributes[$name]) ? $this->attributes[$name] : '');
	}

	/**
	 * Get DOM style attribute value without property name.
	 * Use in $this->getStyleAttribute() and can also be used in $this->markup()
	 *
	 * @return string Style attribute value
	 */
	private function getStyleString()
	{
		$css = '';

		foreach ( $this->attributes['style'] as $prop => $value) {
			$css .= $prop . ':' . $value . ';';
		}

		return $css;
	}
}