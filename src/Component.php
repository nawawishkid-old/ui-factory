<?php

namespace UIFactory;

use Exception;

/**
 * Base class for component class to extends
 */
abstract class Component
{
	/**
	 * @var array $props Array of default properties for building this component.
	 */
	protected $props = [];

	/**
	 * @var array $prorequiredPropsps Array of required properties for building this component.
	 */
	protected $requiredProps = [];

	/**
	 * @var array $requiredValidationProps Array of expected client's property value. Use to restrict client's property value
	 */
	protected $requiredValidationProps = [];

	/**
	 * @var array $availablePropValidationRules Different type of prop validation rules. Use in Component::getPropValidationRuleName()
	 */
	private static $availablePropValidationRules = [
		'type', 'in', 'not_in'
	];

	protected $markupCallbacks = [];

	protected $html = '';

	/**
	 * @var array $configs Array of component's configuration
	 */
	protected static $configs = [
		'PROP_VALIDATION' => false,
		'PROP_CONTENT_SUFFIX' => 'Content',
		'PROP_CONTENT_PREPEND_SUFFIX' => 'Before',
		'PROP_CONTENT_APPEND_SUFFIX' => 'After'
	];

	/**
	 * Abstract function for returning HTML markup of this component
	 *
	 * @param \stdClass $props Component's property
	 * @return string HTML markup
	 */
	abstract protected function markup($props) : string;

	/**
	 * Set theme and echo this component if requires
	 *
	 * @uses Component::prop() to set component's properties
	 * @uses Component::print() to echo component
	 * @param mixed $echo Echo the component immediately?
	 * @return void
	 */
	public function __construct(array $props = [], $echo = true)
	{
		$this->markupCallbacks[] = [$this, 'markup'];
		$this->initContentSiblingProps($this->props);

		if (! empty($props)) {
			$this->editProps($props);
		}

		if ($echo) {
			$this->render();
		}
	}

	public function __get($name)
	{
		return isset($this->props[$name]) ? $this->props[$name] : null;
	}

	/**
	 * Return component markup
	 *
	 * @api
	 * @uses Component::getHTML() to actually get component's markup
	 * @param int $amount Number of component's duplication
	 * @return string HTML markup of this component
	 */
	public function get()
	{
		$this->make();
		return $this->html;
	}

	/**
	 * Echo component's markup
	 *
	 * @api
	 * @uses Component::markup() to get component HTML markup
	 * @return void
	 */
	public function render()
	{
		$this->make();
		echo $this->html;
	}

	/**
	 * Component configurations
	 *
	 * @api
	 * @param string $name Name of configuration you want to get/set
	 * @param mixed $value If set, you get the config value. Otherwise, you set it
	 * @return mixed
	 */
	public function config(string $name, $value = null)
	{
		if (! isset(self::$configs[$name])) {
			return null;
		}

		if (is_null($value)) {
			return self::$configs[$name];
		}

		self::$configs[$name] = $value;
		return $this;
	}

	/**
	 * Make the component behaves differently in specific condition
	 *
	 * @api
	 * @param callable $callback Callback for client to make condition
	 * @return Component
	 */
	public function condition(callable $callback)
	{
		call_user_func_array($callback, [$this]);
		return $this;
	}

	/**
	 * 
	 *
	 * @api
	 * @param
	 * @return
	 */
	public function editProps(array $props)
	{
		foreach ($props as $key => $value) {
			if (! $this->config('PROP_VALIDATION')) {
				if (isset($this->props[$key]) || in_array($key, array_values($this->requiredProps))) {
					$this->props[$key] = $value;
				}
			}

			if (isset($this->props[$key]) || in_array($key, array_values($this->requiredProps))) {
				$this->validateRequiredValidationProp($key, $value);
				$this->props[$key] = $value;
			}
		}

		return $this;
	}

	/**
	 *
	 *
	 * @api 
	 * @param 
	 * @param 
	 * @return 
	 */
	public function content(string $name, $content)
	{
		$this->props[$name . self::$configs['PROP_CONTENT_SUFFIX']] = $this->getClientContent($content);
		return $this;
	}

	/**
	 *
	 *
	 * @api 
	 * @param 
	 * @param 
	 * @return 
	 */
	public function prepend(string $name, $content)
	{
		$this->injectContentSibling($name, $this->getClientContent($content), false);
		return $this;
	}

	/**
	 *
	 *
	 * @api 
	 * @param 
	 * @param 
	 * @return 
	 */
	public function append(string $name, $content)
	{
		$this->injectContentSibling($name, $this->getClientContent($content));
		return $this;
	}

	/**
	 *
	 *
	 * @api 
	 * @param 
	 * @param 
	 * @return 
	 */
	public function prependChild(string $name, $content)
	{
		$this->injectContentChild($name, $this->getClientContent($content), false);
		return $this;
	}

	/**
	 *
	 *
	 * @api 
	 * @param 
	 * @param 
	 * @return 
	 */
	public function appendChild(string $name, $content)
	{
		$this->injectContentChild($name, $this->getClientContent($content));
		return $this;
	}

	private function injectContentChild(string $name, $content, $append = true)
	{
		$prop =& $this->props[$name . self::$configs['PROP_CONTENT_SUFFIX']];
		$prop = $this->concatString($prop, $content, $append);
	}

	private function injectContentSibling(string $name, $content, $append = true)
	{
		$suffix = $append ? 'PROP_CONTENT_APPEND_SUFFIX' : 'PROP_CONTENT_PREPEND_SUFFIX';

		$prop =& $this->props[$name . self::$configs[$suffix]];
		$prop = $this->concatString($prop, $content, $append);
	}

	private function concatString(string $old_string, string $new_string, $append = true)
	{
		return $append ? $old_string . $new_string : $new_string . $old_string;
	}

	private function getClientContent($content)
	{
		return is_callable($content) 
					? call_user_func_array($content, [(object) $this->props, $this]) 
					: $content;
	}

	protected function initContentSiblingProps($props)
	{
		$content_suffix_length = mb_strlen(self::$configs['PROP_CONTENT_SUFFIX']);

		foreach ($props as $key => $value) {
			$content_suffix = mb_substr($key, -$content_suffix_length, $content_suffix_length);

			if ($content_suffix === self::$configs['PROP_CONTENT_SUFFIX']) {
				$name = $this->extractPropContentName($key);

				$this->props[$name . self::$configs['PROP_CONTENT_PREPEND_SUFFIX']] = '';
				$this->props[$name . self::$configs['PROP_CONTENT_APPEND_SUFFIX']] = '';
			}
		}
	}

	private function extractPropContentName($prop)
	{
		return mb_substr($prop, 0, mb_strlen($prop) - mb_strlen(self::$configs['PROP_CONTENT_SUFFIX']));
	}

	/**
	 * Assign component's markup to Component::$html
	 *
	 * @uses Component::checkRequiredProps() to validate client-given property
	 * @uses Component::makeMultiple() to assign multiple component's duplication to Component::$html
	 * @uses Component::markup() to get component HTML markup
	 * @param int $amount @see Component::makeMultiple()
	 * @return string HTML markup
	 */
	protected function make($amount = 1)
	{
		$this->checkRequiredProps();

		$html = '';

		foreach ($this->markupCallbacks as $callback) {
			$html .= call_user_func_array($callback, [(object) $this->props]);
		}

		$this->html = $html;

		return $this;
	}

	/**
	 * Check whether client has given all required properties. If not, throw an error
	 *
	 * @uses Component::getComponentNameFromClass()
	 * @return void
	 */
	private function checkRequiredProps()
	{
		$prop_name = array_keys($this->props);

		foreach ($this->requiredProps as $opt) {
			if (! in_array($opt, $prop_name)) {
				$name = $this->getComponentNameFromClass($this);

				throw new Exception("Component '$name' requires prop '$opt'.");
				
			}
		}
	}

	/**
	 * Qualification of component's restricted properties. In other words, client-given property validation.
	 *
	 * @uses Component::getPropValidationRuleName() to get prop validation rule
	 * @uses Component::requiredValidationPropTypeIs() to check type of client-given prop
	 * @uses Component::requiredValidationPropIsIn() to check if client-given prop is one of specific value
	 * @uses Component::requiredValidationPropIsNotIn() to check if client-given prop is not one of specific value
	 * @param string $name Key of UIFactory\Components\Component::$requiredValidationProps to get validation rule
	 * @param mixed $value Value of client-given prop to validate
	 * @return void
	 */
	private function validateRequiredValidationProp(string $name, $value)
	{
		if (! isset($this->requiredValidationProps[$name])) {
			return;
		}

		$rule = $this->requiredValidationProps[$name];
		$rule_name = $rule[0];
		$rule_value = $rule[1];

		if (! $this->propValidationRuleNameIsValid($rule_name)) {
			throw new Exception("'$rule_name' is not a valid prop validation rule.");
		}

		switch ($rule_name) {
			case 'type':
				$valid = $this->requiredValidationPropTypeIs($rule_value, $value);
				break;
			case 'in':
				$valid = $this->requiredValidationPropIsIn($rule_value, $value);
				break;
			case 'not_in':
				$valid = $this->requiredValidationPropIsNotIn($rule_value, $value);
				break;
		}

		if ($valid !== true) {
			throw new Exception($valid);
			
		}
	}

	/**
	 * Validate client-given prop by type of prop
	 *
	 * @param string $rule_value Validation rule from UIFactory\Components\Component::$requiredValidationProps
	 * @param mixed $value Client-given value
	 * @return bool|string True, if client-given prop is valid. Otherwise, error string to use in exception
	 */
	private function requiredValidationPropTypeIs($rule_value, $value)
	{
		$type = ['string', 'array', 'bool', 'int', 'float', 'callable'];

		if (is_string($rule_value)) {
			$valid = gettype($value) === $rule_value;
		} elseif (is_array($rule_value)) {
			$valid = in_array(gettype($value), $rule_value);
		}

		if (! $valid) {
			return "Type of given prop must be $rule_value, " . gettype($value) . " given.";
		}

		return true;
	}

	/**
	 * Validate that client-given prop must be one of specific value specified in inherited class of UIFactory\Components\Component
	 *
	 * @param string $rule_value Validation rule from UIFactory\Components\Component::$requiredValidationProps
	 * @param mixed $value Client-given value
	 * @return bool|string True, if client-given prop is valid. Otherwise, error string to use in exception
	 */
	private function requiredValidationPropIsIn(array $rule_value, $value)
	{
		if (! in_array($value, $rule_value)) {
			return "Given prop must be one of " . implode(', ', $rule_value);
			
		}

		return true;
	}

	/**
	 * Validate that client-given prop must NOT be one of specific value specified in inherited class of UIFactory\Components\Component
	 *
	 * @param string $rule_value Validation rule from UIFactory\Components\Component::$requiredValidationProps
	 * @param mixed $value Client-given value
	 * @return bool|string True, if client-given prop is valid. Otherwise, error string to use in exception
	 */
	private function requiredValidationPropIsNotIn(array $rule_value, $value)
	{
		if ($this->requiredValidationPropIsIn($rule_value, $value) === true) {
			return "Given prop must not be one of " . implode(', ', $rule_value);
		}

		return true;
	}

	/**
	 * Get prop validation rule
	 *
	 * @param string $name Key of UIFactory\Components\Component::$requiredValidationProps to get validation rule
	 * @return string|array Validation rule from UIFactory\Components\Component::$requiredValidationProps
	 */
	private function getPropValidationRuleName(string $name)
	{
		$rule_name = $this->requiredValidationProps[$name][0];

		if (! in_array($rule_name, self::$availablePropValidationRules)) {
			throw new Exception("'$rule_name' is not a valid prop validation rule.");
			
		}

		return $rule_name;
	}

	private function propValidationRuleNameIsValid(string $name)
	{
		if (! in_array($name, self::$availablePropValidationRules)) {
			return false;
		}

		return true;
	}

	protected function getComponentNameFromClass($component)
	{
		$string = is_string($component) ? $component : get_class($component);

		preg_match('/\w+$/', $string, $matches);

		return strtolower($matches[0]);
	}
}