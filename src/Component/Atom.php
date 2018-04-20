<?php

namespace UIFactory\Component;

use UIFactory\Theme;

abstract class Atom extends Base
{
	const CSS_CLASS_PREFIX = 'uif-a-';

	/**
	 * Base's HTML markup
	 *
	 * @return string HTML markup
	 */
	// abstract protected function markup() : string;

	public function __construct(array $props = [], Theme $theme = null, $echo = 1)
	{
		// $this->addAttributes(['class' => self::CSS_CLASS_PREFIX . $this->getBaseNameFromClass($this)]);
		parent::__construct($props, $theme, $echo);
	}
}