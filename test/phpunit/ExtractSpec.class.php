<?php

require_once __DIR__.'/../RobotBaseSpec.class.php';

class ExtractSpec extends RobotBaseSpec {
    public function testNormal() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'extract')
			->then('Success')
            ->when('Exec', 'report --for drops')
            ->then('Contains', '9,9     T')
            ->when('Exec', 'extract')
            ->then('Success')
            ->when('Exec', 'report --for drops')
            ->then('Contains', '9,9     T T')
	;}

    /* Borderline */

    public function testInvalidArgumentsRobotId() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('RobotId', '111')
			->when('Exec', 'extract 111')
			->then('Failure')
	;}

    public function testInvalidNotOwnRobot() {
		$this
			->given('Genesis')
				->and('User', 'Mob')
                ->and('Robot', 'tea')
			->when('Exec', 'extract')
			->then('Failure')
	;}

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
        return 'extract';
    }
    
}
