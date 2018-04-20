<?php

namespace UIFactory;

use UIFactory\Component\Atom;
use UIFactory\Component\Molecule;

abstract class Factory
{
	/**
	 * @var array $themes List of available themes
	 */
	protected $themes = [];

	/**
	 * @var array $theme Current theme
	 */
	protected $theme;

	protected $libraryURIs = [];
	protected $cssSrcs = [];
	protected $jsSrcs = [];

	abstract public function button(array $props = [], $echo = 1) : Atom;
	abstract public function form(array $props = [], $echo = 1) : Molecule;
	abstract public function textField(array $props = [], $echo = 1) : Atom;
	// abstract public function select() : Atom\Select;
	// abstract public function checkbox() : Atom\Checkbox;

	/**
	 * Add theme to the factory to immediately use it or just storing
	 *
	 * @uses Factory::useTheme() to use the given theme immediately
	 *
	 * @param Theme $theme Theme instance to add
	 * @param mixed $use Use the added theme immediately?
	 * @return Factory
	 */
	public function addTheme(Theme $theme, $use = false)
	{
		$this->themes[$theme->name] = $theme;

		if ($use) {
			$this->useTheme($theme->name);
		}

		return $this;
	}

	/**
	 * Use one of available theme by giving it a name
	 *
	 * @param string $name Available theme name
	 * @return Factory
	 */
	public function useTheme(string $name)
	{
		$this->theme = $this->themes[$name];
		return $this;
	}

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