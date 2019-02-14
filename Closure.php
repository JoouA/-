<?php
// 定义一个闭包， 并把它赋给变量
$f = function() {
	return 7;
};

// 使用闭包
$f();

function testClosure(Closure $callback) {
	return $callback;
}

// $f 作为参数传递给函数 testClosure，如果是普遍函数是没有办法作为testClosure的参数的
echo testClosure($f)() . "\n";  // 7

// 也可以直接将定义的闭包作为参数传递，而不用提前赋给变量
echo testClosure(function() {
	echo "laravel China";
})() . "\n";  // laravel China


// 闭包不止可以做函数的参数，也可以作为函数的返回值

function getClosure() {
	return function() {
	 return 7; 
	};
}

$c = getClosure();

echo $c(); // 7

echo "\n";




?>