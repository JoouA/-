<?php
namespace App\Controllers;

function register($class_name)
{
	require_once($class_name . '.php');
}

spl_autoload_register('\\App\\Controllers' . '\\' . 'register');

$obj  = new Controller1();
// $obj2 = new MyClass2();