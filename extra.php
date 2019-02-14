<?php

print_r($_GET);

$arr = [
	'name' => 'lijun',
	'age' => 30
]; 

extract($arr);

echo $name;

echo $age;
?>