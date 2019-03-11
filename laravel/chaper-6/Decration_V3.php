<?php
interface Step
{
	public static function go(Closure $next);
}

class FirstStep implements Step
{
	public static function go(Closure $next)
	{
		echo '开启Session 获取数据' . PHP_EOL;
		$next();
		echo '保存数据 关闭session' . PHP_EOL;
	}
}

function goFun($step, $className)
{
	/*print_r($step);
	print_r($className);*/
	return function() use($step, $className)
	{
		return $className::go($step);
	};
}

function then()
{
	$steps = ['FirstStep'];

	$prepare = function () {
		echo '请求向路由器传递，返回响应' . PHP_EOL;
	};

	$go = array_reduce($steps, 'goFun', $prepare);


	// print_r($go);

	$go();
}

then();



$a = [1];
 
$result =  array_reduce($a, function($sum, $item) {
	echo $sum . PHP_EOL;
	return $sum + $item;
}, '21212');

print_r($result);
