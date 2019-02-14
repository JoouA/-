<?php
class test
{
    public static function getinfo()
    {
        var_dump(func_get_args());
    }
}
 
call_user_func(array('test', 'getinfo'), 'hello world');

//eg array_walk array_map preg_replace_callback etc
 
echo preg_replace_callback('~-([a-z])~', function ($match) {
    return strtoupper($match[1]);
}, 'hello-world');

echo "\n";

$greet = function($name)
{
    printf("Hello %s\r\n", $name);
};
 
$greet('World');
$greet('PHP');