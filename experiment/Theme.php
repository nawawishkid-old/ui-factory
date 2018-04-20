<?php

namespace UIFactory;

use Exception;

class Theme
{
	/**
	 * @var string $name Theme name
	 */
	protected $name = '';

	/**
	 * @var array $colors Theme color scheme
	 */
	protected $colors = [];

	/**
	 * @var array $spacings Theme spacing value e.g. padding, margin
	 */
	protected $spacings = [];

	/**
	 * Set theme name
	 *
	 * @param string $name Theme name
	 * @return void
	 */
	public function __construct(string $name)
	{
		$this->name = $name;
	}

	/**
	 * Get magic method
	 */
	public function __get($name)
	{
		return $this->{$name};
	}

	/**
	 * Set/get color
	 *
	 * @uses Theme::value() to retrieve the value of specified property name
	 *
	 * @param string|array $arg Give string name to get value, array to set.
	 * @return Theme|string If set value, return $this. If get value, return string
	 */
	public function color($arg)
	{
		return $this->value('colors', $arg);
	}

	/**
	 * Set/get spacing
	 *
	 * @uses Theme::value() to retrieve the value of specified property name
	 *
	 * @param string|array $arg Give string name to get value, array to set.
	 * @return Theme|string If set value, return $this. If get value, return string
	 */
	public function spacing($arg)
	{
		return $this->value('spacings', $arg);
	}

	/**
	 * Set/get theme property
	 *
	 * @param string $prop_name Property name you want to get or set
	 * @param string|array $arg Use string to get value, array to set e.g. ['name' => 'value']
	 * @return Theme|string If set value, return $this. If get value, return string
	 */
	protected function value(string $prop_name, $arg)
	{
		if (! in_array($prop_name, array_keys(get_class_vars(self::class)))) {
			throw new Exception("Unknown '{$prop_name}' theme property");
		}

		if (is_string($arg)) {
			return $this->{$prop_name}[$arg];
		}

		if (is_array($arg)) {
			foreach ($arg as $name => $value) {
				$this->{$prop_name}[$name] = $value;
			}
		}

		return $this;
	}

	/**
	 * Get inverted color of specified color name in Theme::$colors
	 *
	 * @uses Theme::getColorType() to get color type
	 * @uses Theme::convertColorHEXtoRGB() to convert HEX to RGB array
	 * @uses Theme::RGBToArray() to convert RGB string to array
	 * @uses Theme::invertRGBColor() to actually invert color
	 *
	 * @param string $color_name Name of color
	 * @param mixed $bw If you want the inverted color to be either black or white, set to true
	 * @return string The inverted color
	 */
	public function getInvertedColor(string $color_name, $bw = false)
	{
		$color = $this->colors[$color_name];
		$color_type = $this->getColorType($color);

		if (! $color_type) {
			throw new Exception(
				"Cannot get contrast color of '$color'. Supported colors are HEX and RGB."
			);
		}

		$rgb_array = $color_type === 'hex' ? $this->convertColorHEXtoRGB($color, true) : $this->RGBToArray($color);

		return $this->invertRGBColor($rgb_array, $bw);
	}

	/**
	 * Get color type (hex or rgb)
	 *
	 * @param string $color Color e.g. #ffffff, rgb(255,255,255)
	 * @return string|bool Color type i.e. 'hex', 'rgb' or false if color not supported
	 */
	protected function getColorType(string $color)
	{
		if (preg_match('/^#[\da-fA-F]{3,6}$/', $color)) {
			return 'hex';
		} elseif (preg_match('/^rgb\(\d{1,3},\d{1,3},\d{1,3}\)$/', $color)) {
			return 'rgb';
		}

		return false;
	}

	/**
	 * Convert RGB string e.g. 'rgb(0,0,0)' to array
	 *
	 * @param string $rgb RGB string
	 * @return array RGB array
	 */
	protected function RGBToArray($rgb)
	{
		return sscanf($rgb, 'rgb(%d,%d,%d)');
	}

	/**
	 * Convert HEX color string to RGB string or array
	 *
	 * @see https://stackoverflow.com/questions/15202079/convert-hex-color-to-rgb-values-in-php
	 *
	 * @param string $hex HEX color string
	 * @param bool $to_array Convert to array or not
	 * @return string|array RGB string or array
	 */
	public function convertColorHEXtoRGB(string $hex, $to_array = false)
	{
		$rgb = sscanf($hex, "#%02x%02x%02x");

		if ($to_array) {
			return $rgb;
		}

		return 'rgb(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ')';
	}

	/**
	 * Invert RGB color from specified color to its complementary color
	 * or either black or white
	 *
	 * @see https://stackoverflow.com/questions/35969656/how-can-i-generate-the-opposite-color-according-to-current-color
	 * 
	 * @param array $rgb RGB array e.g. [255,255,255]
	 * @param mixed $bw Convert to either black or white
	 * @return string Hex color
	 */
	protected function invertRGBColor(array $rgb, $bw = false)
	{
		if ($bw) {
			return ($rgb[0] * 0.299 + $rgb[1] * 0.587 + $rgb[2] * 0.114) > 186
			            ? '#000000'
			            : '#FFFFFF';
		}

		$hex = '#';

		foreach ($rgb as $value) {
			$little_hex = dechex(255 - $value);
			$hex .= strlen($little_hex) < 2 ? '0' . $little_hex : $little_hex;
		}

		return $hex;
	}
}