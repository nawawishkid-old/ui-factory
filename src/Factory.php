<?php

namespace UIFactory;

use UIFactory\Component\Base;

class Factory
{
	protected $cssSrcs = [];
	protected $jsSrcs = [];

	// // Examples method signature
	// public function button(array $props = [], $echo = 1) : Base;
	// public function textField(array $props = [], $echo = 1) : Base;

	public function script($src = null, $include = false)
	{
		$this->sourceTagArgumentHandling('js', $src, $include);
	}

	public function style($src = null, $include = false)
	{
		$this->sourceTagArgumentHandling('css', $src, $include);
	}

	protected function sourceTagArgumentHandling(string $type, $src_name = null, $include = false)
	{
		$src = is_null($src_name) ? $this->{$type . 'Srcs'} : $this->{$type . 'Srcs'}[$src_name];

		if (is_array($src)) {
			foreach ($src as $name => $source) {
				$this->getSourceTag($type, $source, $include);
			}

			return;
		}

		$this->getSourceTag($type, $src_name, $include);
	}

	protected function getSourceTag(string $type, string $src, $include = false)
	{
		if ($type === 'js') {
			$tag = 'script';
			$src_attr = 'src';
			$attr = '';
		} elseif ($type === 'css' && ! $include) {
			$tag = 'link';
			$src_attr = 'href';
			$attr = 'rel="stylesheet" type="text/css"';
		} else {
			$tag = 'style';
			$attr = '';
		}

		if ($include) {
			echo "<$tag>";
			include $src;
			echo "</$tag>";

			return;
		}

		echo "<$tag $attr {$src_attr}=\"$src\"></$tag>";
	}
}