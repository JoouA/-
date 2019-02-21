<?php

$keys  = ["sky", "grass", "orange"];

$values = ["blue", "green", "orange"];

// $key和$values数组的长度需要一致
$combile_arr = array_combine($keys, $values);

print_r($combile_arr);

// array_keys  输出数组中的key值
print_r(array_keys($combile_arr));

// array_values 输出数组中的value值
print_r(array_values($combile_arr));

// array_flip  将数组中的键和值的位置互相交换
$rever_arr = array_flip($combile_arr);
print_r($rever_arr);

// array_map
$callback = function($value1, $value2) {
	return $value1. " the color is" . $value2; 
};

$new_array =  array_map($callback, $keys, $values);

print_r($new_array);
