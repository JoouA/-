<?php
 class Factory
 {
 	private $_factory;

 	public function set($id, $value)
 	{
 		$this->_factory[$id] = $value;
 	}

 	public function get($id)
 	{
 		$value = $this->_factory[$id];
        return $value;
 	}
 }

class User
{
	private $_username;

	public function __construct($username = '')
	{
		$this->_username = $username;
	}

	public function username()
	{
		return $this->_username;
	}
}

$factory = new Factory();

$factory->set('jack', function() {
	return new User('jack');
});

$factory->set('jooua', function() {
	return new User('jooua');
});

echo $factory->get('jack')()->username();

echo "\n";

echo  $factory->get('jooua')()->username();

$factory->set('weitao', new User('weitao'));

echo "\n";

echo $factory->get('weitao')->username();