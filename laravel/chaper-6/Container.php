<?php
class Container
{
	// 用于封装提供实例的回调函数，真正的容器还会封装实例等其它内容
	public $bindings = [];

	public function bind($abstract, $concrete = null, $shared = false)
	{
		if (! $concrete instanceof Closure) {
			$concrete = $this->getClosure($abstract, $concrete);
		}

		
		$this->bindings[$abstract]  = compact('concrete', 'shared');
	}

	// 绑定接口和生成相对应实例的回调函数

	protected function getClosure($abstract, $concrete)
	{
		return function($c) use ($abstract, $concrete) {
			$method = ($abstract == $concrete) ? 'build' : 'make';
		
			return $c->$method($concrete);
		};
	}

	// 生成实例对象，首先要解决接口和实例化类之间的依赖关系
	public function make($abstract)
	{
		$concrete = $this->getConcrete($abstract);
		
		if ($this->isBuildable($concrete, $abstract)) {
			echo "build" . PHP_EOL;
			$object = $this->build($concrete);
		} else {
			$object = $this->make($concrete);
		}

		return $object;
	}

	protected function isBuildable($concrete, $abstract)
	{
		return $concrete === $abstract || $concrete instanceof Closure;
	}



	// 获取绑定的回调函数
	protected function getConcrete($abstract)
	{
		if (! isset($this->bindings[$abstract])) {
			return $abstract;
		}

		return $this->bindings[$abstract]['concrete'];
	}


	// 实例化对象
	public function build($concrete)
	{
		if ($concrete instanceof Closure) {
			return $concrete($this);
		}

		echo "build function";
		$reflector = new ReflectionClass($concrete);


		if (! $reflector->isInstantiable()) {
			echo $message = "Target [$concrete] is not instantiable.";
		}

		$constructor = $reflector->getConstructor();


		if (is_null($constructor)) {
			return new $concrete;
		}

		$dependencies = $constructor->getParameters();
		$instances = $this->getDependencies($dependencies);

		return $reflector->newInstanceArgs($instances);
	}

	// 解决反射机制实例化对象时的依赖
	protected function getDependencies($parameters)
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

	protected function resoveClass(ReflectionParameter $parameter)
	{
		return $this->make($parameter->getClass()->name);
	}
}

function __autoload($file)
{
	require $file . '.php';
}

$app = new Container();

$app->bind('Visit', 'Leg');

$app->make('Visit')->go();
// print_r($app->make('Visit')->go());
// print_r($app);

/*$app->bind('666', 'Traveller');

print_r($app);

$tra =  $app->make('666');

print_r($app);

print_r($tra->goTravel());*/