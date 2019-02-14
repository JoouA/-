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

	        array_unshift($parameters, $this);

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