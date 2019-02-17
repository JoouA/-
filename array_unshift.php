<?php
/*class A
{
	protected $a = [1,2,3];


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

print_r($value[0]->getArray());*/
// $value[0]->getArray();


interface Method 
{
	public function run();
}

class A implements Method
{

	public function run()
	{
		echo "A run";
	}
}

class B implements Method
{

	public function run()
	{
		echo "B run";
	}
}


class C implements Method
{

	public function run()
	{
		echo "C run";
	}
}

class Man
{	
	private $run;

	public function __construct(Method $run)
	{
		$this->run = $run;
	}

	public function run()
	{
		$this->run->run();
	}
}


$a = new A;

$man = new Man($a);

$man->run();

?>