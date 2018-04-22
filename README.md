# PHP UIFactory

เครื่องมือที่จะช่วยให้คุณสามารถเชียน user interface component เก็บไว้ใช้ซ้ำได้ และยังสามารถปรับแต่ง component ที่สร้างไว้ได้อย่างไร้ขีดจำกัด เช่น คุณเขียน card component แต่ตอนเอาไปใช้ซ้ำ คุณอาจจะเปลี่ยนให้มันกลายเป็น button ก็ยังได้ (คงไม่มีใครทำอ่ะครับ แค่ยกตัวอย่างเฉย ๆ) ความยืดหยุ่นในการปรับแต่งจะมากน้อยแค่ไหนก็ขึ้นอยู่กับคุณจะเขียน component ไว้ยังไง  

การใช้งาน UI Factory จะแบ่งเป็น 2 ส่วนหลักนะครับ ส่วนแรกคือการ*สร้างและใช้งาน* component อีกส่วนคือการ*ปรับแต่ง* component ที่สร้างไว้

### 1. การสร้างและใช้งาน component
เราสร้าง component ได้ 2 วิธี คือ:
1. เขียน `class` ที่ `extends` `UIFactory\Component` `class` หรือ
2. สร้าง `object` ใหม่จาก `Base` `class`

#### 1.1 Extends `Component` class
ลองมาดูภาพรวมของ `Button` component class ที่เขียนเสร็จแล้ว ใช้งานได้เลย กันก่อนครับ:
```
<?php

namespace BootstrapUI;

use UIFactory\Component;

class Button extends Component
{
	public function markup($props) : string
	{
		return '<button class="btn btn-primary">Click me!</button>';
	}
}
```
แค่นี้ละครับ ได้ละ basic component ที่ใช้ Bootstrap เป็น CSS library  
แต่ถ้าจะเขียนแค่นี้ ไม่ต้องไป extends class อื่นให้วุ่นวายหรอกเนอะ จับ markup string ใส่ใน global function ก็ใช้ได้ละ:
```
function button() {
	return '<button class="btn btn-primary">Click me!</button>';
}
```

งั้นเราลองมาดู `Button` class อีกอันข้างล่างนี้ครับ:
```
class Button extends Component
{
	private $props = [
		'label' => 'Click me!'
		'class' => 'btn btn-primary'
	];

	public function markup($props) : string
	{
		return (
<<<HTML
<button class="$props->class">
	$props->label
</button>
HTML
		);
	}
}
```
จากโค้ดข้างบน `$props` คือ property ของ component ของเรา ตรงตัวเลยนะครับ `$props` เป็น property ของ `Button` แล้วมันคืออะไร? มันคือ array ที่เอาไว้เก็บการตั้งค่าของ component ครับ   
อันต่อมาคือ `markup` method เอาไว้ `return` HTML markup ของ component ของเรา string ที่ได้จาก method นี้จะถูกเอาไป `echo` เพื่อแสดงผลบนหน้าจอต่อไปครับ  
ดูแค่นี้ก็พอจะนึกออกแล้วใช่ไหมครับว่า `$props` กับ `markup` ทำงานร่วมกันยังไง เปรียบเทียบง่าย ๆ ได้ว่า `$props` คือโกดังเก็บอะไหล่แต่ง `markup` คือรถยนต์ และบางส่วนของรถยนต์ก็มีป้ายกำกับไว้ด้วยว่าจะใช้อะไหล่อันไหนในโกดังมาปรับแต่ง  
เราทำแค่เอาอะไหล่ที่จะใช้มาเตรียมไว้ในโกดัง เอารถที่จะแต่งมา แล้วแปะ post-it ไว้ว่าจะให้เปลี่ยนอะไรตรงไหนบ้าง เท่านี้เอง ส่วนการถอดประกอบส่งมอบรถยนต์เป็นหน้าที่ของอู่หรือก็คือ UI Factory นั่นเองครับ  
เวลาจะใช้ button นี้ก็แค่
```
<?php

use BootstrapUI\Button;

$button = new Button();
```
HTML output:
```html
<button class="btn btn-primary">Click me!</button>
```

#### 1.2 สร้าง object จาก `Base` class
```
<?php

use UIFactory\Components\Base;

$button = new Base();
$button->addProps([
			'label' => 'Click me!',
			'class' => 'btn btn-primary'
		])
		->addMarkup(function($props) {
			return "<button class=\"$props->class\">$props->label</button>";
		})
		->render();
```
อืม... แต่นี้แหละครับ ไม่ต้องอธิบายเนาะ ผลลัพธ์ที่ได้เหมือน `Button` class ข้างบนนั่นทุกประการ

#### แล้วเมื่อไหร่ควร extends `Component` หรือสร้าง `Base` object?


### 2. การปรับแต่ง
```
<?php

use BootstrapUI\Button;

$btn = new Button([], 0);
$btn->render(); // default

$btn->editProps([
		'label' => "Don't click me!",
		'class' => 'btn-dont-click'
	])
	->render();
```
HTML output:
```html
<button class="btn btn-primary">Click me!</button>
<button class="btn btn-primary btn-dont-click">Don't click me!</button>
```
ยิ่ง `$props` เยอะ ยิ่งแต่งได้เยอะ period! 555