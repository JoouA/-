<?php
class Traveller
{
	private $visit;

	public function __construct(Visit $visit)
	{
		$this->visit = $visit;
	}

	public function goTravel()
	{
		$this->visit->go();
	}
}

