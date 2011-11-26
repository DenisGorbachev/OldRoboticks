<?php

require_once __DIR__.'/../BaseSpec.class.php';

class ExtractSpec extends BaseSpec {
	public function testNormal() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Exec', 'extract '.$this->getRobotId('tea'))
			->then('Success')
				->and('DropsReport', 'Contains', 'T')
            ->when('Exec', 'extract '.$this->getRobotId('tea'))
            ->then('Success')
				->and('DropsReport', 'Contains', 'T T')
	;}

    /* Borderline */

    public function testInvalidArgumentsRobotId() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Exec', 'extract 111')
			->then('Failure')
	;}

    public function testInvalidNotOwnRobot() {
		$this
			->given('Genesis')
				->and('User', 'Mob')
			->when('Exec', 'extract '.$this->getRobotId('tea'))
			->then('Failure')
	;}

	public function testInvalidNonExtractiveRobot() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Exec', 'extract '.$this->getRobotId('grunt'))
			->then('Failure')
				->and('DropsReport', 'NotContains', 'T')
	;}
	
	public function testInvalidNonExtractiveSector() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
            ->when('Moves', '--relative '.$this->getRobotId('tea').' 1,0')
			->when('Exec', 'extract '.$this->getRobotId('tea'))
			->then('Failure')
				->and('DropsReport', 'NotContains', 'T')
	;}

}
