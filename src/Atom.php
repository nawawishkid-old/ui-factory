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

	public function __construct(Theme $theme, $echo = true)
	{
		$this->attributes['class'] = self::CSS_CLASS_PREFIX . $this->getComponentNameFromClass($this);
		parent::__construct($theme, $echo);
	}
}