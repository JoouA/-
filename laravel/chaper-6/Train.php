<?php
require_once "Visit.php";
class Train implements Visit
{
	public function go()
	{
		echo "take tarin to beijing!";
	}
}