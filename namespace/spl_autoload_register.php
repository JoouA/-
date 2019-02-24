<?php
/*spl_autoload_register(function($class) {

	$class_map = array(
		'os\\Linux' => './Linux.php'
	);

	$file = $class_map[$class];

	if (file_exists($file)) {
		include $file;
	}
});

new \os\Linux();*/


$class = 'app\view\news\Index';

/* 顶级命名空间路径映射 */
$vendor_map = array(
    'app' => 'C:\Baidu',
);

/* 解析类名为文件路径 */
$vendor = substr($class, 0, strpos($class, '\\')); // 取出顶级命名空间[app]
$vendor_dir = $vendor_map[$vendor]; // 文件基目录[C:\Baidu]
$rel_path = dirname(substr($class, strlen($vendor))); // 相对路径[/view/news]

/*echo substr($class, strlen($vendor)) . "\n";
echo $rel_path;
die();*/
$file_name = basename($class) . '.php'; // 文件名[Index.php]

/* 输出文件所在路径 */
echo $vendor_dir . $rel_path . DIRECTORY_SEPARATOR . $file_name;