<?php 

$a = ['one', 'two', 'three'];

print_r($a);


$a = (object)$a;

print_r($a);

print_r(reset($a));

function hah(...$b)
{
	echo array_sum($b);
}

hah([1,2,3,4]);