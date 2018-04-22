# PHP UIFactory

เครื่องมือที่จะช่วยให้คุณสามารถเชียน user interface component เก็บไว้ใช้ซ้ำได้ และยังสามารถปรับแต่ง component ที่สร้างไว้ได้อย่างไร้ขีดจำกัด เช่น คุณเขียน card component แต่ตอนเอาไปใช้ซ้ำ คุณอาจจะเปลี่ยนให้มันกลายเป็น button ก็ยังได้ (คงไม่มีใครทำอ่ะครับ แค่ยกตัวอย่างเฉย ๆ) ความยืดหยุ่นในการปรับแต่งจะมากน้อยแค่ไหนก็ขึ้นอยู่กับคุณจะเขียน component ไว้ยังไง  

การใช้งาน tool นี้จะแบ่งเป็น 2 ส่วนนะครับ ส่วนแรกคือการ*สร้าง* component อีกส่วนคือการ*ใช้งาน* component ที่สร้างไว้

### 1. การสร้าง component
เราสร้าง component ได้ 2 วิธี คือ:
1. เขียน `class` ที่ `extends` `UIFactory\Component` `class` หรือ
2. สร้าง `object` ใหม่จาก `Base` `class`

#### 1.1 Extends `Component` class
ลองมาดูภาพรวมของ Button component class ที่เขียนเสร็จแล้ว ใช้งานได้เลย กันก่อนครับ:
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

งั้นเราลองมาดู Button class อีกอันข้างล่างนี้ครับ:
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
จากโค้ดข้างบน `$props` คือ property ของ component ของเรา ตรงตัวเลยนะครับ `$prop` เป็น property ของ `Button` ซึ่งมันก็คือ array ที่เอาไว้เก็บการตั้งค่าของ component  
อันต่อมาคือ `markup` method เอาไว้ `return` HTML markup ของ component ของเรา string ที่ได้จาก method นี้จะถูกเอาไป `echo` เพื่อแสดงผลบนหน้าจอครับ