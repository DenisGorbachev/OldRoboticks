<?php

require_once __DIR__.'/../RobotBaseSpec.class.php';

class PickSpec extends RobotBaseSpec {
    public function testNormal() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'report --for drops')
			->then('Contains', '9,8     K')
			->when('Exec', 'drop M')
			->when('Exec', 'mv 9,8')
            ->when('Exec', 'pick K')
			->when('Exec', 'report --for drops')
			->then('NotContains', '9,8     K')
	;}

    /* Borderline */

	public function testInvalidNonPickableRobot() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'pear')
			->when('Exec', 'pick A')
			->then('Failure')
            ->when('Exec', 'ls')
            ->then('NotContains', '11,6     A')
	;}
	
	public function testInvalidNonPickableLetter() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
            ->when('Exec', 'drop M')
            ->when('Exec', 'pick A')
			->then('Failure')
            ->when('Exec', 'ls')
            ->then('NotContains', '9,9      A')
	;}

    public function testInvalidPickMoreThanOneLetter() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'teeter')
            ->when('Exec', 'drop N')
            ->when('Exec', 'drop Z')
            ->when('Exec', 'pick NZ')
			->then('Failure')
            ->when('Exec', 'ls')
            ->then('NotContains', '4,19     NZ')
	;}

    public function getRobotTestCommand() {
        return 'pick A';
    }
    
}
