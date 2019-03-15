<?php
interface Visit
{
	public function go();
}

class Leg implements Visit
{
	private $name;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function go()
	{
		echo $this->name .  'walk to beijing!' . PHP_EOL;
	}
}

class Train implements Visit
{
	public function go()
	{
		echo 'take a high way to beijing!' . PHP_EOL;
	}
}

class Air implements Visit
{
	public function go()
	{
		echo 'fly to beijing!' . PHP_EOL;
	}
}

class Traveller 
{
	private $way;

	public function __construct(Visit $way)
	{
		$this->way = $way;
	}

	public  function go()
	{
		$this->way->go();
	}
}


class Container
{
	protected $bindings = [];


	public function bind($abstract, $concrete)
	{
		if (! $concrete instanceof Closure) {
			// 将concrete转换成构造函数
			$concrete = $this->getClosure($abstract, $concrete);
		}

		$this->bindings[$abstract]['concrete'] = $concrete;
	}

	// 构成匿名函数
	private function getClosure($abstract, $concrete)
	{
		return function($container) use($abstract, $concrete) {

			$method =  $abstract == $concrete ? 'make' : 'build';
							
			return $container->$method($concrete);	
		};
	}

	// 生成实例 首先先解决接口和实例化类之间的依赖问题
	public function make($abstract)
	{
		$concrete = $this->getConcrete($abstract);

		if ($this->isBuildAble($concrete, $abstract)) {
			$object = $this->build($concrete);
		} else {
			$object = $this->make($concrete); 
		}

		return $object;
	}

	private function build($concrete)
	{
		if ($concrete instanceof Closure) {
			return $concrete($this);
		}

		$reflector = new ReflectionClass($concrete);

		if (! $reflector->isInstantiable()) {
			throw new Exception("$concrete cant be instantiable");
		}

		$constructor = $reflector->getConstructor();


		if (is_null($constructor)) {
			return new $concrete;
		}

		$dependencies = $constructor->getParameters();

		$instances = $this->getDependencies($dependencies);

		return $reflector->newInstanceArgs($instances);
	}

	private function getDependencies($parameters)
	{
		$dependencies = [];

		foreach ($parameters as $parameter) {
			$dependency = $parameter->getClass();


			if (is_null($dependency)) {
				$dependencies[] = null;
			} else {
				$dependencies[] = $this->resoveClass($parameter);
			}
		}

		return (array)$dependencies;
	}

	private function resoveClass(ReflectionParameter $parameter)
	{
		return $this->make($parameter->getClass()->name);
	}


	private function getConcrete($abstract)
	{
		if (! isset($this->bindings[$abstract]['concrete'])) {

			return $abstract;
		}

		return $this->bindings[$abstract]['concrete'];
	}

	private function isBuildAble($concrete, $abstract)
	{
		return $abstract == $concrete || $concrete instanceof Closure;
	}
}

$app = new Container();
$app->bind('Visit', function($c) {
	return new Leg('xiaofang');
});

$app->make('Traveller')->go();

