<?php

namespace UIFactory\Component;

use UIFactory\Theme;

abstract class Atom extends Common
{
	const CSS_CLASS_PREFIX = 'uif-a-';

	/**
	 * Component's HTML markup
	 *
	 * @return string HTML markup
	 */
	// abstract protected function markup() : string;

	public function __construct(array $options = [], Theme $theme = null, $echo = 1)
	{
		$this->addAttributes(['class' => self::CSS_CLASS_PREFIX . $this->getComponentNameFromClass($this)]);
		parent::__construct($options, $theme, $echo);
	}
}