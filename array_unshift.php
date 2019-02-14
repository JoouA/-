<?php
class A
{
	protected $a = [];


	public function shift($value)
	{
		array_unshift($value, $this);

		return $value;
	}

	public function getArray()
	{
		echo "123456789";
		// print_r($this->a);
	}
} 

$a = new A();
$value =  $a->shift(['haha']);
$value[0]->getArray();
?>