<?php

namespace BootstrapUI;

use UIFactory\Component;

class Image extends Component
{
	protected $props = [
		'wrapper_class' => '',
		'wrapper_style' => '',
		'src' => '',
		'img_class' => '',
		'img_style' => '',
		'filter' => false,
		'filter_class' => '',
		'filter_content' => '',
		'link' => '',
		'link_blank' => false
	];

	public function markup($props) : string
	{
		$filter = $props->filter ? "<div class=\"bg-filter $props->filter_class\">$props->filter_content</div>" : '';
		$has_filter = $props->filter ? 'has-bg-filter' : '';

		if (! empty($props->link)) {
			$link_open = "<a href=\"$props->link\">";
			$link_close = "</a>";
		} else {
			$link_open = '';
			$link_close = '';
		}

		return (
<<<HTML
<div class="$props->wrapper_class $has_filter" style="$props->wrapper_style">
	$filter
	$link_open
	<img src="$props->src" class="$props->img_class" style="$props->img_style">
	$link_close
</div>
HTML
		);
	}
}