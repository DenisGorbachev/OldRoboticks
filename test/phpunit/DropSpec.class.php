<?php

require_once __DIR__.'/../RobotBaseSpec.class.php';

class DropSpec extends RobotBaseSpec {
    public function testNormal() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->then('NotContains', '9,9     M')
			->when('Exec', 'drop M')
			->then('Success')
			->when('Exec', 'report --for drops')
			->then('Contains', '9,9     M')
	;}

    /* Borderline */

	public function testInvalidNonDroppableRobot() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'pear')
			->when('Exec', 'drop M')
			->then('Failure')
            ->when('Exec', 'report --for drops')
            ->then('NotContains', '11,6     M')
	;}
	
    public function getRobotTestCommand() {
        return 'drop';
    }
    
}
