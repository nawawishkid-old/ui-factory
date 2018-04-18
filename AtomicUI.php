<?php

namespace UIFactory\Component;

use UIFactory\Theme;

abstract class AtomicUI extends CommonUI
{
	/**
	 * Component's HTML markup
	 *
	 * @return string HTML markup
	 */
	// abstract protected function markup() : string;

	public function __construct(Theme $theme, $echo = true)
	{
		parent::__construct($theme, $echo);
	}
}