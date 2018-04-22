<?php

namespace UIFactory;

use UIFactory\Component;
use UIFactory\Components\Base;

class Factory
{
	/**
	 * @var array $cssSrcs CSS sources for this factory 
	 * @example ['bootstrap' => 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css']
	 */
	protected $cssSrcs = [];

	/**
	 * @var array $jsSrcs JavaScript sources for this factory
	 * @example ['jquery' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js']
	 */
	protected $jsSrcs = [];

	// // Examples method signature
	// public function button(array $props = [], $echo = 1) : Base;
	// public function textField(array $props = [], $echo = 1) : Base;

	/**
	 * API for echoing HTML <script> using $this->jsSrcs as a source
	 *
	 * @api
	 * @uses Factory::sourceTagArgumentHandling() to handle different type of argument
	 * @param string|array|null $src_name @see Factory::echoSourceTag()
	 * @param mixed $include @see Factory::echoSourceTag()
	 * @return void
	 */
	public function script(string $src_name = null, $include = false)
	{
		$this->sourceTagArgumentHandling('js', $src_name, $include);
	}

	/**
	 * API for echoing HTML <style> or <link> using $this->cssSrcs as a source
	 *
	 * @api
	 * @uses Factory::sourceTagArgumentHandling() to handle different type of argument
	 * @param string|array|null $src_name @see Factory::echoSourceTag()
	 * @param mixed $include @see Factory::echoSourceTag()
	 * @return void
	 */
	public function style($src_name = null, $include = false)
	{
		$this->sourceTagArgumentHandling('css', $src_name, $include);
	}

	/**
	 * Decides whether to echo single, multiple, or all sources based on given argument type
	 *
	 * @uses Factory::echoSourceTag() to actually echo source tag
	 * @param string $type @see Factory::echoSourceTag()
	 * @param string|array|null $src_name  @see Factory::echoSourceTag()
	 * @param mixed $include  @see Factory::echoSourceTag()
	 * @return void
	 */
	protected function sourceTagArgumentHandling(string $type, $src_name = null, $include = false)
	{
		$src = is_null($src_name) ? $this->{$type . 'Srcs'} : $this->{$type . 'Srcs'}[$src_name];

		if (is_array($src)) {
			foreach ($src as $name => $source) {
				$this->echoSourceTag($type, $source, $include);
			}

			return;
		}

		$this->echoSourceTag($type, $src_name, $include);
	}

	/**
	 * Echo script or style tag based on given argument.
	 *
	 * @param string $type Tag type to echo i.e. 'js' or 'css'
	 * @param string|array|null $src_name Name or array of names of factory's CSS. Null or blank to echo all
	 * @param mixed $include Get source using 'include' function or not
	 * @return
	 */
	protected function echoSourceTag(string $type, string $src, $include = false)
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

	public function renderMany(Component $component, int $amount, callable $callback = null)
	{
		foreach (range(1, $amount) as $index) {
			call_user_func_array($callback, [$component, $index]);
			$component->render();
		}

		return $this;
	}
}