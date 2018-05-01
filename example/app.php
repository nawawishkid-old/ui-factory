<?php

use UIFactory\Components\Base;

$time = -microtime(true);

$bs = new BootstrapUI\Factory;

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
	<?php $bs->style(); ?>
	<style type="text/css">
		section {
			padding: 1em;
			margin-bottom: 1em;
		}
		section > div {
			padding: 1em 0 1em;
			border-bottom: 1px solid;
		}
	</style>
</head>
<body>
	<?php
		$page = new Base();
		$page->addMarkup(function($props, $that) {
			// $help = ($that->row)(5);
			// $help = call_user_func_array($that->row, [5]);
			$help = $that->helper('row', [5]);
			return (
<<<HTML
<h1 class="text-center p-3">$props->header</h1>
<div class="row no-gutters">
	<div class="col">$props->col1</div>
	<div class="col">$props->col2</div>
	<div class="col">$props->col3</div>
</div>
$help
HTML
			);
		})->addProps([
			'header' => 'New page!',
			'col1' => '<p class="text-center">Column 1</p>',
			'col2' => '<p class="text-center">Column 2</p>',
			'col3' => '<p class="text-center">Column 3</p>',
		])->addHelper('row', function($amount = 1) {
			$html = '';

			foreach (range(1, $amount) as $key => $value) {
				$html .= '<p>Run by <code>addHelper</code> API</p>';
			}

			return $html;
		}); //->render();
	?>
	<main class="row no-gutters p-3">
		<div class="col-2 position-relative">
			<nav class="position-fixed">
				<a href="#button" class="d-block">Button</a>
				<a href="#text-field" class="d-block">Text field</a>
				<a href="#card" class="d-block">Card</a>
			</nav>
		</div>
		<div class="col-10">
			<section id="button">
				<?php
					function button_markup($props) {
						return "<button class=\"btn btn-primary $props->class\">$props->content</button>";
					}

					$button = new Base();
					$button->addMarkup('button_markup')
							->addRequiredValidationProps([
								'class' => ['type', 'string'],
								'content' => ['type', ['string', Base::class]]
							])
						   	->addProps([
								'class' => 'my-custom-button-class',
								'content' => 'Click me!'
							])->render();
					// output: <button class="btn btn-primary my-custom-button-class">Click me!</button>

					$button->editProps([
						'class' => 'actual-class',
						'content' => 'Don\'t click me!'
					])->render();
					// output: <button class="btn btn-primary actual-class">Don't click me!</button>

					$button->render();
				?>
			</section>
			<?php // $page->editProps(['col1' => 'Hello,', 'col2' => 'darkness', 'col3' => 'my old friend'])->render(); ?>
			<section id="card">
				<h3>Card</h3>
				<div>
					<h4>Default</h4>
					<?php
						$card = $bs->card([], 0);
						$card->render();

						$bs->renderMany($card, 3, function($component, $index) {
							$component->editProps([
								'titleContent' => 'Card #' . $index
							]);
						});
					?>
				</div>
				<div>
					<h4>Header</h4>
					<?php
						$card->editProps([
							'headerContent' => '<b>Header, set with editProps()</b>',
							'headerTag' => 'div',
							'footerContent' => 'Footer'
						])->prependChild('header', 'Inject content using prependIn(). ')
						  ->appendChild('header', '. Inject content using appendIn()')
						  ->prepend('header', '<a href="#">Inject content using prepend()</a>')
						  ->append('header', '<a href="#">Inject content using append()</a>')
						  ->prepend('header', function($props) {
						  	return '<p>Inject using <code>prepend()</code> with <code>anonymous function</code></p>';
						  })
						  ->append('header', function($props, $that) {
						  	return "<p>Use <code>" . '$props' . "</code> in <code>anonymous function</code> to display header's content: <i>$props->headerContent</i></p>" . $that->text($props);
						  })
						  ->prepend('footer', 'Hello, ')
						  ->appendChild('footer', $button->get())
						  ->content('title', 'hello, there!')
						  ->prependChild('title', '<b>Prepend!</b>')
						  ->editProps([
						  	'textClass' => 'blockquote'
						  ])
						  ->render();
					?>
				</div>
				<div>
					<h4>Footer</h4>
					<?php
						$card->editProps([
							'headerContent' => '',
							'footerContent' => 'Footer',
						])->render();
					?>
				</div>
			</section>
			<section id="text-field">
				<h3>Text field</h3>
				<div>
					<h4>Normal</h4>
					<?php
						$txt = $bs->textField([], 0);
						$txt->config('PROP_VALIDATION', true)
							->editProps([
								'name' => 'textNormal',
								'id' => 'textNormal',
								'label' => 'Normal text field',
								'placeholder' => 'Normal',
							])
							->render();

						// foreach (range(1, 10) as $index) {
						// 	$txt->editProps([
						// 		'id' => 'textInput-' . $index,
						// 		'name' => 'textInput-' . $index,
						// 		'label' => 'Normal text field #' . $index,
						// 		'placeholder' => 'Normal #' . $index,
						// 	])->render();
						// }
					?>
					<h4>Without label</h4>
					<?php
						$bs->textField([
							'name' => 'textNoLabel',
							'id' => 'textNoLabel',
							'label' => false,
							'placeholder' => 'No label here',
						]);
					?>
					<h4>With help text</h4>
					<?php
						$bs->textField([
							'name' => 'textHelp',
							'id' => 'textHelp',
							'label' => 'Text field with help text below',
							'placeholder' => 'See help text below',
							'help_text' => 'This is help text'
						]);
					?>
				</div>
				<div>
					<h4>Size</h4>
					<h5>Large</h5>
					<?php
						$bs->textField([
							'name' => 'textSizeLg',
							'id' => 'textSizeLg',
							'label' => 'Large text field',
							'placeholder' => "I'm large...!",
							'size' => 'lg'
						]);
					?>
					<h5>Default</h5>
					<?php
						$bs->textField([
							'name' => 'textSizeDefault',
							'id' => 'textSizeDefault',
							'label' => 'Default size text field',
							'placeholder' => "I'm default :)",
						]);
					?>
					<h5>Small</h5>
					<?php
						$bs->textField([
							'name' => 'textSizeSm',
							'id' => 'textSizeSm',
							'label' => 'Small text field',
							'placeholder' => "I'm small",
							'size' => 'sm'
						]);
					?>
				</div>
				<div>
					<h4>Read-only</h4>
					<?php
						$bs->textField([
							'name' => 'text5',
							'id' => 'text5',
							'label' => 'Read-only text field',
							'placeholder' => 'Just read',
							'readonly' => true,
							'value' => 'Just read me'
						]);
					?>
					<h4>Read-only plaintext</h4>
					<?php
						$bs->textField([
							'name' => 'text6',
							'id' => 'text6',
							'label' => 'Read-only plaintext text field',
							'placeholder' => 'Just read',
							'plaintext' => true,
							'value' => 'Just read me'
						]);
					?>
				</div>
				<div>
					<h4>Disabled</h4>
					<?php
						$bs->textField([
							'name' => 'textDisabled',
							'id' => 'textDisabled',
							'label' => 'Disabled text field',
							'placeholder' => 'Disabled',
							'disabled' => true
						]);
					?>
				</div>
				<div>
					<h4>Password</h4>
					<?php
						$bs->password([
							'name' => 'password1',
							'id' => 'password1',
							'placeholder' => 'Password',
							'label' => 'Password'
						]);
					?>
				</div>
				<div>
					<h4>Validation</h4>
					<form class="needs-validation" novalidate>
						<?php
							$bs->textField([
								'name' => 'textValidate',
								'id' => 'textValidate',
								'label' => 'Text field with help text below',
								'placeholder' => 'See help text below',
								'help_text' => 'This is help text',
								'required' => true,
								'valid' => 'Correct!',
								'invalid' => 'Incorrect!'
							]);
							$bs->button([
								'label' => 'Submit'
							]);
						?>
					</form>
				</div>
			</section>
			<section id="checkbox">
				<h3>Checkbox & radio</h3>
				<div>
					<h4>Radio</h4>
					<?php
						$bs->radio([
							'id' => 'radioStack1',
							'name' => 'radioStack1',
							'value' => 'radioStack1',
							'label' => 'Radio stack #1',
							'checked' => true
						]);
						$bs->radio([
							'id' => 'radioStack2',
							'name' => 'radioStack2',
							'value' => 'radioStack2',
							'label' => 'Radio stack #1'
						]);
						$bs->radio([
							'id' => 'radioStackDisabled',
							'name' => 'radioStackDisabled',
							'value' => 'radioStackDisabled',
							'label' => 'Radio stack #3 (disabled)',
							'disabled' => true
						]);
					?>
				</div>
				<div>
					<h4>Radio inline</h4>
					<?php
						$bs->radio([
							'id' => 'radioInline',
							'name' => 'radioInline',
							'value' => 'radioInline',
							'label' => 'Radio inline #1',
							'inline' => true,
							'checked' => true
						]);
						$bs->radio([
							'id' => 'radioInline2',
							'name' => 'radioInline2',
							'value' => 'radioInline2',
							'label' => 'Radio inline #2',
							'inline' => true
						]);
						$bs->radio([
							'id' => 'radioInlineDisabled',
							'name' => 'radioInlineDisabled',
							'value' => 'radioInlineDisabled',
							'label' => 'Radio inline #3 (disabled)',
							'inline' => true,
							'disabled' => true
						]);
					?>
				</div>
				<div>
					<h4>Checkbox</h4>
					<form>
					<?php
						$bs->checkbox([
							'id' => 'checkboxStack1',
							'name' => 'checkboxStack1',
							'value' => 'checkboxStack1',
							'label' => 'Checkbox stack #1',
							'checked' => true
						]);
						$bs->checkbox([
							'id' => 'checkboxStack2',
							'name' => 'checkboxStack2',
							'value' => 'checkboxStack2',
							'label' => 'Checkbox stack #1'
						]);
						$bs->checkbox([
							'id' => 'checkboxStackDisabled',
							'name' => 'checkboxStackDisabled',
							'value' => 'checkboxStackDisabled',
							'label' => 'Checkbox stack #3 (disabled)',
							'disabled' => true
						]);
					?>
					</form>
				</div>
				<div>
					<h4>Checkbox inline</h4>
					<?php
						$bs->checkbox([
							'id' => 'checkboxInline',
							'name' => 'checkboxInline',
							'value' => 'checkboxInline',
							'label' => 'Checkbox inline #1',
							'inline' => true,
							'checked' => true
						]);
						$bs->checkbox([
							'id' => 'checkboxInline2',
							'name' => 'checkboxInline2',
							'value' => 'checkboxInline2',
							'label' => 'Checkbox inline #2',
							'inline' => true
						]);
						$bs->checkbox([
							'id' => 'checkboxInlineDisabled',
							'name' => 'checkboxInlineDisabled',
							'value' => 'checkboxInlineDisabled',
							'label' => 'Checkbox inline #3 (disabled)',
							'inline' => true,
							'disabled' => true
						]);
					?>
				</div>
			</section>

				<?php
					// $x = new BootstrapUI\Button();

					// $bs->button();

					// $bs->radio([
					// 	'name' => 'radio2',
					// 	'id' => 'radio2',
					// 	'label' => 'My radio 2',
					// 	'value' => 'radio1',
					// 	'wrapper_class' => 'wrapper-class'
					// ]);
					// $bs->radio([
					// 	'name' => 'radio3',
					// 	'id' => 'radio3',
					// 	'label' => 'My radio 3',
					// 	'value' => 'radio1',
					// 	'wrapper_class' => 'wrapper-class'
					// ], 0)->condition(function ($that) {
					// 	if (10 < 100) {
					// 		$that->editProps(['label' => 'oooooooo']);
					// 	}
					// })->render();

					// $bs->fileBrowser([
					// 	'label' => "Don't choose file!",
					// 	'id' => 'file1',
					// 	'name' => 'file1'
					// ]);

					// $bs->select([
					// 	'label' => "Please, don't select me",
					// 	'name' => 'select1',
					// 	'id' => 'select1',
					// 	'prop' => [
					// 		'one' => 'One',
					// 		'two' => 'Two',
					// 		'three' => 'Three'
					// 	]
					// ]);
				?>
	</main>

	<?php
		$bs->script();
	?>

	<h1><?php echo 'Execution time: ' . ($time += microtime(true)); ?></h1>
</body>
</html>

