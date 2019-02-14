<?php

/*
Closure {   
    public static Closure bind (Closure $closure , object $newthis [, mixed $newscope = 'static' ])  
    public Closure bindTo (object $newthis [, mixed $newscope = 'static' ])  
} 
方法说明：
Closure::bind： 复制一个闭包，绑定指定的$this对象和类作用域。
Closure::bindTo： 复制当前闭包对象，绑定指定的$this对象和类作用域。
下面将介绍Closure::bind和Closure::bindTo
参数和返回值说明：
closure：表示需要绑定的闭包对象。
newthis：表示需要绑定到闭包对象的对象，或者NULL创建未绑定的闭包。
newscope：表示想要绑定给闭包的类作用域，可以传入类名或类的示例，默认值是 'static'， 表示不改变。
该方法成功时返回一个新的 Closure 对象，失败时返回FALSE。
*/

class Animal
{
	private static $cat = 'cat';

	private $dog = 'dog';

	public $pig = 'pig';
} 

// 获取Animal类静态私有成员变量
$cat = static function() {
	return Animal::$cat;
};

$dog = function() {
	return $this->dog;
};

$pig = function() {
	return $this->pig;
};

$bindCat = Closure::bind($cat, null, new Animal());// 给闭包绑定了Animal实例的作用域，但未给闭包绑定$this对象

print_r($bindCat());

echo "\n";

$bindDog = Closure::bind($dog, new Animal(), 'Animal');// 给闭包绑定了Animal类的作用域，同时将Animal实例对象作为$this对象绑定给闭包  
print_r($bindDog());




echo "\n";

$bindPig = Closure::bind($pig, new Animal(), 'Animal');// 将Animal实例对象作为$this对象绑定给闭包,保留闭包原有作用域 

echo $bindPig() . "\n";

$animal = new Animal();

echo "\n";

/*echo $bindCat(),'<br>';// 根据绑定规则，允许闭包通过作用域限定操作符获取Animal类静态私有成员属性  
echo $bindDog(),'<br>';// 根据绑定规则，允许闭包通过绑定的$this对象(Animal实例对象)获取Animal实例私有成员属性  
echo $bindPig(),'<br>';// 根据绑定规则，允许闭包通过绑定的$this对象获取Animal实例公有成员属性*/

// bindTo与bind类似，是面向对象的调用方式，这里只举一个，其他类比就可以
$bindCat = $cat->bindTo(null, 'Animal');

echo $bindCat();

echo "\n";

$bindDog = $dog->bindTo(new Animal, 'Animal');

echo $bindDog();

echo "\n";

$bindPig = $pig->bindTo(new Animal);

echo $bindPig();

echo "\n";

?>