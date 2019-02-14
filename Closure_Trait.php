<?php

trait DynamicTrait
{
	public function __call($name, $args)
	{
		if (is_callable($this->name)) {
			return call_user_func($this->name, $args);
		} else {
			throw new \RuntimeException("method {$name} does not exists！");	
		}
	}

	public function __set($name, $value)
	{
		$this->name = is_callable($value) ? $value->bindTo($this, $this) : $value;
	}
}

/** 
 * 只带属性不带方法动物类
 * 
 * @author 疯狂老司机
 */
class Animal {
    use DynamicTrait;
    private $dog = 'dog';
}
 
$animal = new Animal;
 
// 往动物类实例中添加一个方法获取实例的私有属性$dog
$animal->getdog = function() {
    return $this->dog;
};
 
echo $animal->getdog();
?>