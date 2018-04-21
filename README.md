# PHP UIFactory

1. Create UI factory.
2. Use it.

### Todo
[ ] Create component's JavaScript mapping


#### Base api methods
- append($component, $content) -- Before component
- prepend($component, $content) -- After component
- appendIn($component, $content) -- Component's first child
- prependIn($component, $content) -- Component's last child
- addAttribute($component_name, $name_value_attributes_array)
```
if $component_name instanceof Base :
	$component_name->addAttribute($compoenent_name, $name_value_attributes_array)
else :
	$this->attributes[$component_name][] = $name_value_attributes_array
```
- content($component_name, $content)
```
$this->props[$component_name . '_content'] = $content
```