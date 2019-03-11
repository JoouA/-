<?php
interface Visit
{
	public function go();
}

class Leg implements Visit
{

	public function go()
	{
		echo  PHP_EOL . 'walk to beijing';
	}
}

class Car implements Visit
{
	public function go()
	{
		echo PHP_EOL . 'driver to beijing.';
	}
}

class  Train implements Visit
{
	public function go()
	{
		echo PHP_EOL . 'take a high way to beijing!';
	}
} 

class Traveller
{
	protected $visitor;

	public function __construct(Visit $visitor)
	{	
		$this->visitor = $visitor;
	}

	public function go()
	{
		$this->visitor->go();
	}
}

class  Container
{
	public $bindings = [];

	public function bind($abstract, $concrete = null)
	{
		if (! $concrete instanceof Closure) {
			$concrete = $this->getClosure($abstract, $concrete);
		}

		return $this->bindings[$abstract] = compact('concrete');
	}

	// 生成回调函数 其中$container 就是Container的对象
	private function  getClosure($abstract, $concrete)
	{
		return function($container) use ($abstract, $concrete) {
			$method = ($abstract == $concrete) ? 'build' : 'make';

			return $container->$method($concrete);
		};
	}
	
	// 生成实例 首先先解决接口和实例化类之间的依赖问题
	public function make($abstract)
	{
		$concrete = $this->getConcrete($abstract);

		if ($this->isBuildable($concrete, $abstract)) {
			$object = $this->build($concrete);
		} else {
			$object = $this->make($concrete);
		}

		return $object;
	}

	// 开始实例化
	public function build($concrete)
	{	
		// 如果不是闭包函数就传参数返回闭包
		if ($concrete instanceof Closure) {
			return $concrete($this);
		}

		// 开始获取反射类
		$reflector = new ReflectionClass($concrete);

		// 判断这个类时候可以被实例化 isInstantiable
		if (! $reflector->isInstantiable()) {
			throw new Exception("$concrete can not be instantiable!");
		}

		// 获取构造函数
		$constructor = $reflector->getConstructor();

		if (is_null($constructor)) {
			return new $concrete;
		}

		// 获取构造函数的参数
		$dependencies = $constructor->getParameters();

		print_r($dependencies);



		$instances = $this->getDependience($dependencies);


		// print_r($instances);

		return $reflector->newInstanceArgs($instances);
	}

	private function getDependience($parameters)
	{
		$dependencies = [];

		foreach ($parameters as $parameter) {
			echo "構造函數參數";
			print_r($parameter);
			// 获取依赖函数的类名
			$dependency = $parameter->getClass();

			echo "构造函数的依赖\n";

			print_r($dependency);
			if (is_null($dependency)) {
				$dependencies[] = null;
			} else {
				echo "解决构造函依赖\n";
				$dependencies[] = $this->resoveClass($parameter);

				print_r($dependencies);
			}
		}

		return (array)$dependencies;
	}

	private function resoveClass(ReflectionParameter $parameter)
	{
		echo "依赖的类\n";
		print_r($parameter->getClass()->name) . PHP_EOL;
		return $this->make($parameter->getClass()->name);
	}


	private function getConcrete($abstract)
	{
		if (! isset($this->bindings[$abstract])) {
			return $abstract;
		}

		return $this->bindings[$abstract]['concrete'];
	}

	private function isBuildable($concrete, $abstract)
	{
		return $concrete == $abstract || $concrete instanceof CLosure;
	}
}

$app = new Container();
$app->bind('Visit', 'Train');

// $app->bind('666', 'Traveller');


// print_r($app);

$app->make('Traveller')->go();