<?php

namespace UIFactory\Component;

abstract class AtomicUI extends CommonUI
{
	/**
	 * @var string Atom's inner HTML
	 */
	protected $content = '';

	/**
	 * Atom's HTML markup
	 *
	 * @return string HTML markup
	 */
	// abstract protected function markup() : string;

	/**
	 * ===========================
	 * Public chained methods
	 * ===========================
	 */
	/**
	 * Set atom's inner HTML
	 *
	 * @param string $content
	 * @return $this
	 */
	public function content(string $content)
	{
		$this->content = $content;
		return $this;
	}

	/**
	 * Append atom's inner HTML
	 *
	 * @param string $content
	 * @return $this
	 */
	public function appendContent(string $content)
	{
		$this->content = $this->content . $content;
		return $this;
	}

	/**
	 * Prepend atom's inner HTML
	 *
	 * @param string $content
	 * @return $this
	 */
	public function prependContent(string $content)
	{
		$this->content = $content . $this->content;
		return $this;
	}
}