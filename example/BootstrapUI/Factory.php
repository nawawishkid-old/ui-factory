<?php

namespace BootstrapUI;

use UIFactory;
use App\Factory\UI\BootstrapUI;

class Factory extends UIFactory\Factory
{
	protected $cssSrcs = [
		'bootstrap' => 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css'
	];

	protected $jsSrcs = [
		'jquery' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js',
		'popper' => 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js',
		'bootstrap' => 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.bundle.min.js',
		'form-submission' => 'form-submission.js'
	];

	public function button(array $props = [], $echo = 1) : UIFactory\Component
	{
		return new Button($props, $echo);
	}

	public function form(array $props = [], $echo = 1) : UIFactory\Component
	{
		return new Form($props, $echo);
	}

	public function textField(array $props = [], $echo = 1) : UIFactory\Component
	{
		return new TextField($props, $echo);
	}

	public function select(array $props = [], $echo = 1) : UIFactory\Component
	{
		return new Select($props, $echo);
	}

	public function fileBrowser(array $props = [], $echo = 1) : UIFactory\Component
	{
		return new FileBrowser($props, $echo);
	}

	public function radio(array $props = [], $echo = 1) : UIFactory\Component
	{
		return new Radio($props, $echo);
	}

	public function checkbox(array $props = [], $echo = 1) : UIFactory\Component
	{
		$props['type'] = 'checkbox';
		return new Radio($props, $echo);
	}

	public function password(array $props = [], $echo = 1) : UIFactory\Component
	{
		$props['type'] = 'password';
		return new TextField($props, $echo);
	}

	public function card(array $props = [], $echo = 1) : UIFactory\Component
	{
		return new Card($props, $echo);
	}
}