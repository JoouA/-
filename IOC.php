<?php
	
	// 定义一个键盘的接口
	interface Board 
	{
        public function type();
    }	

    // 普通键盘
    class CommonBoard implements Board 
    {
        public function type(){
            echo '普通键盘';
        }
    }

    // 机械键盘
    class MechanicalKeyboard implements Board
    {
        public function type(){
            echo '机械键盘';
        }
    }

    // 电脑类
    class Computer 
    {
        protected $keyboard;

        public function __construct (Board $keyboard) {
            $this->keyboard = $keyboard;
        }

        public function getBoardType()
        {
        	echo $this->keyboard->type();
        }
    }


	class Container
	{
	    public $binds;

	    public $instances;

	    public function bind($abstract, $concrete)
	    {
	        if ($concrete instanceof Closure) {
	            $this->binds[$abstract] = $concrete;
	        } else {
	            $this->instances[$abstract] = $concrete;
	        }
	    }

	    public function make($abstract, $parameters = [])
	    {
	        if (isset($this->instances[$abstract])) {
	            return $this->instances[$abstract];
	        }

	       	// 把函数是讲参数加入到数组当中,加入到数组的第一个位置 该函数会返回数组中元素的个数。

	        array_unshift($parameters, $this);

	        // 将$parameters的参数传入到匿名函数当中 	
	        return call_user_func_array($this->binds[$abstract], $parameters);
	    }
	}


	 $container = new Container;
	 $container->bind('CommonBoard', function($container) {
	 	return new CommonBoard;
	 });

	 $container->bind('MechanicalKeyboard', function($container) {
	 	return new MechanicalKeyboard;
	 });

	 $container->bind('Computer', function($container, $module) {
		 	 return new Computer($container->make($module));
	 });


	 $computer = $container->make('Computer',['CommonBoard']);

	 print_r($computer->getBoardType());

?>