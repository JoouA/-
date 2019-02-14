<?php

/**
 * 
 */
class B
{
	
	function __construct()
	{
		# code...
	}

	public function say()
	{
		echo "I am B say function";
	}
}

class A
{

	public function __construct(B $b)
	{

	}

	public function test()
	{
		return function($a) {
			echo $a;		
		}; 
	}
}

$reflector = new ReflectionClass('A');

$constructor = $reflector->getConstructor();

$dependencies = $constructor->getParameters();

print_r($dependencies);


// foreach ($dependencies as $dependencie) {
	// $B =  $dependencie->getClass()->name;
	$B =  $dependencies[0]->getClass()->name;


	$obj_b = new $B;

	$obj_b->say();
// }

?>