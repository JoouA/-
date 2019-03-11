<?php
interface Decorator
{
	public function display();
}

class XiaoFang implements Decorator
{
	private $name;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function display()
	{
		echo '我是' . $this->name  . '我出門了' . PHP_EOL;
	}
}

class Finery implements Decorator
{

	private $componment;

	public function __construct(Decorator $componment)
	{
		$this->componment = $componment;
	}

	public function display()
	{
		$this->componment->display();
	}
}

class Shoes extends Finery
{
	public function display()
	{
		echo "穿上鞋子" . PHP_EOL;

		parent::display();
	}
}

class Shirt extends Finery
{
	public function display()
	{
		echo '穿上裙子' . PHP_EOL;

		parent::display();
	}
}


class Fire extends Finery
{
	public function display()
	{
		echo '出發前我們先理髮' . PHP_EOL;

		parent::display();

		echo '出門后再整理一下頭髮' . PHP_EOL;
	}
}


$xiaofang = new XiaoFang('小芳');

$shoes = new Shoes($xiaofang);

$shirt = new Shirt($shoes);


$fire = new Fire($shirt);

$fire->display();

