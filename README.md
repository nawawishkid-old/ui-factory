# PHP UIFactory

1. Create UI factory.
2. Use it.

### Todo
[x] Edit prop validation rule in UIFactory\Component\Base to make it accepts multiple property data types. Need to restructure $requiredValidationProps array
[x] Enforce inherited class of Base to declare all default $props for avoiding the use of Base::prop() as much as possible. Use Base::$props to get component's prop instead of calling Base::prop() for a better speed performance.
[ ] Create component's JavaScript mapping