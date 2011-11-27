<?php

require_once __DIR__.'/BaseSpec.class.php';

abstract class ScanBaseSpec extends BaseSpec {

    abstract public function getCommand();

    /* Borderline */

    public function testInvalidArgumentsRobotId() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Exec', $this->getCommand().' 111')
			->then('Failure')
	;}

    public function testInvalidNotOwnRobot() {
		$this
			->given('Genesis')
				->and('User', 'Mob')
			->when('Exec', $this->getCommand().' '.$this->getRobotId('tea'))
			->then('Failure')
	;}	

}
