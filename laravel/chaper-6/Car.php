<?php
require_once "Visit.php";
class Car implements Visit
{
	public function go()
	{
		echo "driver to beiijing!";
	}
}