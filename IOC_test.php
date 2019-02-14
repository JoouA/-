<?php
	class Demoa
	{
		public function __construct()
		{
			// echo "I am Demoa Class!";
		}

		public function say()
		{
			echo "I am A function!\n";
		}
	}

	class Demob
	{
		private $a;
		public function __construct(Demoa $a)
		{
			$this->a = $a;
			// echo "I am Demob Class!";
		}

		public function say()
		{
			$this->a->say();
		}
	}

	class B
	{

	}

	class A
	{
		public function __construct(B $args)
		{

		}

		public function doSomething()
		{
			echo 'hello world';
		}
	}


	// 建立class A的反射

	$reflection = new ReflectionClass('A');

	$b = new B();

	// 获取A的实例
	$instance = $reflection->newInstanceArgs([$b]);

	$instance->doSomething(); // 输出'Hello World'

	$construct = $reflection->getConstructor(); //获取class A 的构造函数

	$dependencies = $construct->getParameters();

	var_dump($dependencies);
?>