<?php
// 支付类接口
interface Pay
{
	public function pay();
}

// 支付宝支付
class Alipay implements Pay
{	
	public function pay()
	{
		echo "I am Alipay";
	}
}

class Wechatpay implements Pay
{
	public function pay()
	{
		echo "I am wechat pay!";
	}
}

// 付款
class PayBill
{
	private $payMethod;

	public function __construct(Pay $pay)
	{
		$this->payMethod = $pay;
	}

	public function payMyBill()
	{
		$this->payMethod->pay();
	}
}

class Container
{
	protected $bindings = [];

	public function bind($abstruct, $concrete = null, $shared = false)
	{	
		// 如果提供的$concrete 不是匿名函数就将此转化成匿名函数
		//如果提供的参数不是回调函数，则产生默认的回调函数
		if (! $concrete instanceof Closure) {
			$concrete = $this->getClosure($abstruct, $concrete);
		}

		$this->bindings[$abstruct] = compact('concrete', 'shared');
	}

	/*
	* 创建回调函数
	*/
	protected function getClosure($abstruct, $concrete)
	{
		return function($c) use ($abstruct, $concrete) {
			$method = ($abstruct == $concrete) ? 'make' : 'build';

			return $c->method($concrete);
		};
	}

	protected function make(abstract)
	{
		// $abstruct 是类名
        $concrete = $this->getConcrete($abstract);

        if($this->isBuildable($concrete, $abstract)) {
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

	 //获取绑定的回调函数
	protected function getConcrete($abstruct)
	{
		if(!isset($this->bindings[$abstract])) {
            return $abstract;
        }

        return $this->bindings[$abstract]['concrete'];
	}

	protected function build($concrete)
	{
		if ($concrete instanceof Closure) {
			return $concrete($this);
		}

		// 获取参数的反射类
		$reflector = new ReflectionClass($concrete);

		if (! $reflector->isInstantiable()) {
			echo $message = "Target [$concrete] is not instantiable";
		}

		// 获取构造函数
		$construct = $reflector->getConstructor();

		if (is_null($construct)) {
			return new $concrete;
		}

		// 获得构造函数的参数 
		$dependencies = $construct->getParameters();

		$instance = $this->getDependencies($dependencies);
	}

	//解决通过反射机制实例化对象时的依赖

	protected function getDependencies($parameters)
	{
		$dependencies = [];

		foreach ($parameters as  $parameter) {
			$dependency = $parameter->getClass();

			if(is_null($dependency)) {
                $dependencies[] = NULL;
            } else {
                $dependencies[] = $this->resolveClass($parameter);
            }
		}
	}

	protected function resolveClass(ReflectionParameter $parameter)
	{	
		 // $parameter->getClass()->name 获取类名
		return $this->make($parameter->getClass()->name);
	}

}

?>