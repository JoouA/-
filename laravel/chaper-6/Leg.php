<?php
require_once 'Visit.php';

class Leg implements Visit
{
	public function go()
	{
		echo "\nwalk to beijing!";
	}
}