<?php
namespace App\Http\Controllers\Auth;

function index()
{
	echo "命名空间" .__NAMESPACE__ . "\n";
}

class Controller
{
	public static function index()
	{
		echo '命名空间' . __NAMESPACE__ . "\n";
	}
}

/*index();

Controller::index();*/