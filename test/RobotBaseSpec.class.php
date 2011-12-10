<?php

require_once __DIR__.'/BaseSpec.class.php';

abstract class RobotBaseSpec extends BaseSpec {
    /* Borderline */

    public function testInvalidDisabledRobot() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
				->and('Realm', 'Universe')
                ->and('Robot', 'sedative')
			->when('Exec', $this->getRobotTestCommand())
			->then('Failure')
	;}

    public function testInvalidNotOwnRobot() {
		$this
			->given('Genesis')
				->and('User', 'Mob')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tea')
			->when('Exec', $this->getRobotTestCommand())
			->then('Failure')
	;}	

    public function testInvalidArgumentsRobotId() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
				->and('Realm', 'Universe')
                ->and('RobotId', '111')
			->when('Exec', $this->getRobotTestCommand())
			->then('Failure')
	;}

    abstract public function getRobotTestCommand();

}
