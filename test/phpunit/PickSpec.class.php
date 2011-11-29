<?php

require_once __DIR__.'/../RobotBaseSpec.class.php';

class PickSpec extends RobotBaseSpec {
    public function testPick() {
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

	public function testInvalidNonExtractiveRobot() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'grunt')
			->when('Exec', 'extract')
			->then('Failure')
            ->when('Exec', 'report --for drops')
            ->then('NotContains', '4,19     T')
	;}
	
	public function testInvalidNonExtractiveSector() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
            ->when('Exec', 'mv --relative 1,0')
			->when('Exec', 'extract')
			->then('Failure')
            ->when('Exec', 'report --for drops')
            ->then('NotContains', '10,9     T')
	;}

    public function getRobotTestCommand() {
        return 'pick';
    }
    
}
