<?php

namespace UIFactory;

use Exception;

class Theme
{
	protected $name = '';
	protected $colors = [];
	protected $spacings = [];
	protected $configs = [
		'auto_contrast_color' => true
	];

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	public function __get($name)
	{
		return $this->{$name};
	}

	public function config(string $name, $value)
	{
		$this->configs[$name] = $value;
	}

	/**
	 * Set/get color
	 *
	 * @param string|array $arg If string, get color. If array, set color.
	 * @return $this|string If set, return $this. If get, return color value.
	 */
	public function color($arg)
	{
		return $this->value('colors', $arg);
	}

	public function spacing($arg)
	{
		return $this->value('spacings', $arg);
	}

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

	public function getContrastColor(string $color_name, $bw = false)
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

	protected function getColorType(string $color)
	{
		if (preg_match('/^#[\da-fA-F]{3,6}$/', $color)) {
			return 'hex';
		} elseif (preg_match('/^rgb\(\d{1,3},\d{1,3},\d{1,3}\)$/', $color)) {
			return 'rgb';
		}

		return false;
	}

	protected function RGBToArray($rgb)
	{
		return sscanf($rgb, 'rgb(%d,%d,%d)');
	}

	/**
	 *
	 *
	 * @link https://stackoverflow.com/questions/15202079/convert-hex-color-to-rgb-values-in-php
	 * @link http://php.net/manual/en/function.sscanf.php
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
	 *
	 *
	 * @see https://stackoverflow.com/questions/35969656/how-can-i-generate-the-opposite-color-according-to-current-color
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