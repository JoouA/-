<?php
interface Visit
{
	public function go();
}

class Leg implements Visit
{
	public function go()
	{
		echo 'Walk to Beijing!' . PHP_EOL;
	}
}

class Air implements Visit
{
	public function go()
	{
		echo 'Fly to Beijing' . PHP_EOL;
	}
}

class Traveller
{
	private $visit;

	public function __construct(Visit $visit)
	{
		$this->visit = $visit;
	}

	public function go()
	{
		$this->visit->go();
	}

}

class Container
{
	protected $bindings  = [];

	public function bind($abstract, $concrete)
	{
		if (! $concrete instanceof Closure) {
			$concrete = $this->getClosure($abstract, $concrete);
		}

		$this->bindings[$abstract]['concrete'] = $concrete;
	}

	private function getClosure($abstract, $concrete)
	{
		return function($container) use ($abstract, $concrete) {
			$method = $abstract == $concrete ? 'make' : 'build';

			return $container->$method($concrete);
		};
	}


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

	private function getConcrete($abstract)
	{
		if (! isset($this->bindings[$abstract]['concrete'])) {
			return $abstract;
		}

		return $this->bindings[$abstract]['concrete'];
	} 

	private function isBuildAble($concrete, $abstract)
	{
		return $concrete == $abstract || $concrete instanceof Closure;
	}

	private function build($concrete)
	{
		if ($concrete instanceof Closure) {
			return $concrete($this);
		}

		$reflector = new ReflectionClass($concrete);

		if (! $reflector->isInstantiable()) {
		 	throw new Exception("concrete can't be instantiable");
		}

		// 获取构造函数
		$constructor = $reflector->getConstructor();

		if (is_null($constructor)) {
			return new $concrete;
		}

		$dependencies = $constructor->getParameters();


		$instances = $this->getDependience($dependencies);

		return $reflector->newInstanceArgs($instances);
	}

	private function getDependience($parameters)
	{
		$dependencies = [];

		foreach ($parameters as  $parameter) {
			$depeny = $parameter->getClass();

			if (is_null($depeny)) {
				$dependencies[] = null;
			} else {
				$dependencies[] = $this->resloveClass($parameter);
			}
		}

		return (array)$dependencies;
	}

	private function resloveClass(ReflectionParameter $parameter)
	{
		return $this->make($parameter->getClass()->name);
	}
}


$app = new Container();

$app->bind('Visit', 'Leg');
$app->bind('555', 'Traveller');
$app->make('555')->go();