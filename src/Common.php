<?php

namespace UIFactory\Component;

use Exception;
use UIFactory\Theme;
use UIFactory\Helper\ComponentDirector;
use UIFactory\Helper\ComponentProperty;

abstract class Common
{
	use ComponentDirector;
	use ComponentProperty;

	/**
	 * @var array HTML attributes
	 */
	protected $attributes = [
		'style' => [],
		'class' => ''
	];

	protected $props = [];

	protected $requiredProps = [];

	protected $propTypes = [];

	private $availablePropTypeRules = [
		'type', 'in', 'not_in'
	];

	protected $configs = [
		'PROP_VALIDATION' => false
	];
	
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
	public function __construct(array $props = [], Theme $theme = null, $echo = 1)
	{
		$this->theme = $theme;
		$this->prop($props);

		if ($echo) {
			$this->print($echo);
		}
	}

	public function get($amount = 1)
	{
		return $this->getHTML($amount);
	}

	/**
	 * Echo markup
	 *
	 * @uses Common::markup() to get component HTML markup
	 *
	 * @return void
	 */
	public function print($amount = 1)
	{
		echo $this->getHTML($amount);
	}

	public function getHTML($amount = 1)
	{
		if (! isset($this->html) || empty($this->html)) {
			$this->make($amount);
		}

		return $this->html;
	}

	/**
	 * Return component markup
	 *
	 * @uses Common::markup() to get component HTML markup
	 *
	 * @return string HTML markup
	 */
	public function make($amount = 1)
	{
		$this->checkRequiredProps();

		if ($amount > 1) {
			$this->makeMultiple($amount);
		} elseif ($amount === 1) {
			$this->html = $this->markup();
		}

		return $this;
	}

	protected function makeMultiple(int $amount)
	{
		$markups = '';

		foreach (range(1, $amount) as $index) {
			$markups .= $this->markup();
		}

		$this->html = $markups;
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

	/**
	 * Component configurations
	 *
	 * @param
	 * @param
	 * @return
	 */
	public function config(string $name, $value)
	{
		if (! isset($this->configs[$name])) {
			return null;
		}

		if (is_null($value)) {
			return $this->configs[$name];
		}

		$this->configs[$name] = $value;
		return $this;
	}

	public function condition(callable $callback)
	{
		call_user_func_array($callback, [$this]);
		return $this;
	}
}