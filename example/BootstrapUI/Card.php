<?php

namespace BootstrapUI;

use UIFactory\Component;

class Card extends Component
{
	protected $props = [
		// Custom content
		'cardContent' => '',
		'headerContent' => '',
		'bodyContent' => '',
		'titleContent' => 'Card title',
		'textContent' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
		'footerContent' => '',

		// Custom class
		'cardClass' => '',
		'headerClass' => '',
		'bodyClass' => '',
		'titleClass' => '',
		'textClass' => '',
		'footerClass' => '',

		// etc.
		'headerTag' => 'h5',
		'titleTag' => 'h4',
	];

	protected $requiredValidationProps = [
		// 'name' => ['type', 'string'],
		// 'size' => ['in', ['sm', 'lg']],
		// 'type' => ['in', ['text', 'password']]
	];

	public function __construct(array $props = [], $echo = 1)
	{	
		parent::__construct($props, $echo);
	}

	protected function markup($props) : string
	{
		$cardContent = $this->card($props->cardContent);
		$header = $this->header($props);
		$body = $this->body($props);
		$footer = $this->footer($props);

		return (
<<<HTML
<div class="card $props->cardClass">
	$cardContent
	$header
	$body
	$footer
</div>
HTML
		);
	}

	protected function card($content)
	{
		if (empty($content)) {
			return '';
		}
		
		return $content;
	}

	protected function header($props)
	{
		if (! empty($props->cardContent) || empty($props->headerContent)) {
			return '';
		}

		return (
<<<HTML
$props->headerBefore
<$props->headerTag class="card-header $props->headerClass">
	$props->headerContent
</$props->headerTag>
$props->headerAfter
HTML
		);
	}

	protected function title($props)
	{
		if (! empty($props->bodyContent) || empty($props->titleContent)) {
			return '';
		}

		return (
<<<HTML
$props->titleBefore
<$props->titleTag class="card-title $props->titleClass">
	$props->titleContent
</$props->titleTag>
$props->titleAfter
HTML
		);
	}

	protected function body($props)
	{
		if (! empty($props->cardContent)) {
			return '';
		}

		$title = $this->title($props);
		$text = $this->text($props);

		return (
<<<HTML
<div class="card-body $props->bodyClass">
	$props->bodyContent
	$title
	$text
</div>
HTML
		);
	}

	public function text($props)
	{
		if (! empty($props->bodyContent)) {
			return '';
		}

		return (
<<<HTML
$props->textBefore
<p class="card-text $props->textClass">
	$props->textContent
</p>
$props->textAfter
HTML
		);
	}

	protected function footer($props)
	{
		if (! empty($props->cardContent) || empty($props->footerContent)) {
			return '';
		}

		return (
<<<HTML
$props->footerBefore
<div class="card-footer $props->footerClass">
	$props->footerContent
</div>
$props->footerAfter
HTML
		);
	}
}