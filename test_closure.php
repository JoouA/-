<?php
class Demo
{

	public $name = 'hello world';


 	//绑定接口和生成相应实例的回调函数
    public function bind() 
    {
        $concrete = $this->getClosure('123', '456');

        print_r($concrete);
    }

	//默认生成实例的回调函数 $abstract = pay $concrete = alipay
    protected function getClosure($abstract, $concrete) 
    {
        return function($c) use ($abstract, $concrete)
        {
           return $c->test();
        };
    }

    public function test()
    {	 
    	return "2121212";
    }
}

$demo = new Demo();

$fun = $demo->bind();

// echo $fun();

?>