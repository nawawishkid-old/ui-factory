<?php

protected $refs = [];

private function initHTMLElementAttributeProps($props)
{
	$attr_suffix_length = mb_strlen(self::$configs['PROP_ELEMENT_CONTENT_SUFFIX']);

	foreach ($props as $key => $value) {
		$attr_suffix = mb_substr($key, -$attr_suffix_length, $attr_suffix_length);

		if ($attr_suffix === self::$configs['PROP_ELEMENT_CONTENT_SUFFIX']) {
			$name = $this->extractPropContentName($key);

			$this->props[$name . self::$configs['PROP_ELEMENT_ATTRIBUTE_SUFFIX']] = [];
		}
	}
}

public function addAttributes(string $name, array $attributes)
{
	$attr =& $this->props[$name . 'Attr'];
	$key = $this->getAttributePropKeyFromName($name);

	if (isset($attributes['style'])) {
		$attributes['style'] = $this->mergeStyleAttributesByName($name, $attributes['style']);
	}

	if (isset($attributes['class'])) {
		$attributes['class'] = $this->concatClassAttributesByName($attributes['class']);
	}

	$attr = array_merge($attr, $attributes);

	return $this;
}

protected function mergeStyleAttributesByName(string $name, array $new_style)
{
	$old_style = $this->getAttributePropValueFromName($name, []);

	return array_merge($old_style, $new_style);
}

protected function concatClassAttributesByName(string $name, string $new_class)
{
	$old_class = $this->getAttributePropValueFromName($name);

	return $old_class . ' ' . $new_class;
}

private function getAttributePropKeyFromName(string $name)
{
	return $name . self::$configs['PROP_ELEMENT_ATTRIBUTE_SUFFIX'];
}

private function getAttributePropValueFromName(string $name, $fallback = '')
{
	$key = $this->getAttributePropKeyFromName($name);
	return $this->getAttributePropValueFromKey($key, $fallback);
}

private function getAttributePropValueFromKey(string $key, $fallback = '')
{
	return isset($this->props[$key]['style']) ? $this->props[$key]['style'] : $fallback;
}

/**********************/
// public function ref(string $name, $attribute_name)
// {
// 	return $this->props[$name . '_' . $attribute_name];
// }

// private function initReferenceAttributesProp()
// {
// 	foreach ($this->refs as $key => $value) {
		
// 	}
// }