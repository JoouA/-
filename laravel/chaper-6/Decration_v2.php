<?php
interface Middleware
{
	public static function handle(Closure $next);
}

class VerifyCsrfToken implements Middleware
{
	public static function handle(Closure $next)
	{
		echo '驗證Csrf-Token' . PHP_EOL;
		$next();
	}
}

class ShareErrorsFromSession implements Middleware
{
	public static function handle(Closure $next)
	{
		echo "如果session中有'errors'变量，则共享他" . PHP_EOL;
		$next();
	}
}

class StartSession implements Middleware
{
	public static function handle(Closure $next)
	{
		echo '开启session获取数据' . PHP_EOL;
		$next();
		echo '保存数据，关闭session' . PHP_EOL;
	}
}

class AddQueueCookiesToResponse implements Middleware
{
	public static function handle(Closure $next)
	{
		$next();
		echo '添加下一次请求需要的cookie' . PHP_EOL;
	}
}

class EncryptCookies implements Middleware
{
	public static function handle(Closure $next)
	{
		echo '对输入的请求cookie进行解密' . PHP_EOL ;
		$next();
		echo '对输出响应的cookie进行加密' . PHP_EOL;
	} 
}

class CheckForMaintenanceMode implements Middleware
{
	public static function handle(Closure $next)
	{
		echo '确定当前程序是否处于维护状态' . PHP_EOL;
		$next();
	} 
}

function getSlice()
{
	return function($stack, $pipe)
	{
		return function() use ($stack, $pipe)
		{
			return $pipe::handle($stack);
		};
	};
}

function then()
{
	$pipes = [
		'CheckForMaintenanceMode',
		'EncryptCookies',
		'AddQueueCookiesToResponse',
		'StartSession',
		'ShareErrorsFromSession',
		'VerifyCsrfToken'
	];

	$firstSlice = function() {
		echo '请求想向路由器传递，返回响应' . PHP_EOL;
	};

	$pipes = array_reverse($pipes);

	call_user_func(array_reduce($pipes, getSlice(), $firstSlice));
	// print_r(array_reduce($pipes, getSlice(), $firstSlice));
	/*print_r(array_reduce($pipes, function($stack, $pipe)
	{
		return function() use ($stack, $pipe)
		{
			return $pipe::handle($stack);
		};
	} , $firstSlice));*/
}

then();