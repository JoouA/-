<?php
interface Visit
{
	public function go();
}

class Leg implements Visit
{
	public function __construct($name = 'lslds')
	{

	}

	public function go()
	{
		echo 'Walk to Beijing' . PHP_EOL;
	}
}

class Train implements Visit
{
	public function go()
	{
		echo 'Take a high way to Beijing' . PHP_EOL;
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
	private $method;

	public function __construct(Visit $method)
	{
		$this->method = $method;
	}

	public function go()
	{
		$this->method->go();
	}
}

class Container
{
	protected $bindings = [];

	public function bind($abstract, $concrete)
	{
		if (! $concrete instanceof Closure) {
			$concrete = $this->getClosure($abstract, $concrete);
		}

		$this->bindings[$abstract]['concrete'] = $concrete;
	}

	/*
	*@ 获取回调函数
	*/
	private function getClosure($abstract, $concrete)
	{
		return function($c) use ($abstract, $concrete) {
			$method = $abstract == $concrete ? 'make' : 'build';

			return $c->$method($concrete);
		};
	}

	public function make($abstract)
	{

		$concrete = $this->getConcrete($abstract);

		if ($this->isBuildable($concrete, $abstract)) {
			$object =  $this->build($concrete);
		} else {
			$object = $this->make($concrete);
		}

		return $object;
	}

	private function build($concrete)
	{	
		// 如果是闭包函数就直接返回即可
		if ($concrete instanceof Closure) {
			return $concrete($this);
		}

		$reflector = new ReflectionClass($concrete);

		if (! $reflector->isInstantiable()) {
			throw new Exception("$concrete cant be instantiable");
		}


		$constructor =  $reflector->getConstructor();

		if (is_null($constructor)) {
			return new $concrete;
		}

		$dependencis = $constructor->getParameters();

		$instances = $this->getDependiences($dependencis);

		return $reflector->newInstanceArgs($instances);

	}


	/*
	* 获取闭包函数
	*/
	private function getConcrete($abstract)
	{
		if (! isset($this->bindings[$abstract]['concrete'])) {
			return $abstract;
		}

		return $this->bindings[$abstract]['concrete'];
	}

	/*
	* @ 判断是否可建
	*/
	private function isBuildable($concrete, $abstract)
	{
		return $concrete == $abstract || $concrete instanceof Closure;
	}

	private function getDependiences($paramaters)
	{
		$dependencis = [];

		foreach ($paramaters as  $paramater) {
			$dependy = $paramater->getClass();

			if (is_null($dependy)) {
				$dependencis[] = null; 
			} else {
				$dependencis[] = $this->resoveClass($paramater);
			}

		}

		return (array)$dependencis;
	}

	private function resoveClass(ReflectionParameter $paramater)
	{
		return $this->make($paramater->getClass()->name);
	}

}

$app = new Container();

$app->bind('Visit', 'Air');

$app->make('Traveller')->go();

$app->make('Leg')->go();