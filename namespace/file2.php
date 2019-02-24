<?php
namespace App\Http\Controllers;

include "file1.php";

function index()
{
	echo '命名空间' . __NAMESPACE__ . "\n";
}

class Controller
{
	static function index()
	{
		echo '命名空间' . __NAMESPACE__ . "\n";
	}
}


index();
\App\Http\Controllers\index();
Auth\index();
\App\Http\Controllers\Auth\index();

Controller::index();
\App\Http\Controllers\Controller::index();
Auth\Controller::index();
\App\Http\Controllers\Auth\Controller::index();
