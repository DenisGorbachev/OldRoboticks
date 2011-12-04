<?php

require_once __DIR__.'/../RobotBaseSpec.class.php';

class RepairSpec extends RobotBaseSpec {
	public function testNormal() {
		return $this
			->given('Genesis')
				->and('User', 'Friend')
                ->and('Robot', 'drake')
            ->when('Exec', 'repair '.$this->getRobotId('sedative').' I')
			->then('Success')
            ->when('Exec', 'report --for drops')
            ->then('NotContains', 'I')
            ->given('User', 'Alice')
            ->given('Robot', 'sedative')
            ->when('Exec', 'mv 9,9')
            ->then('Success')
	;}

	/* Borderline */

    public function testInvalidArgumentsNotALetter() {
        return $this
			->given('Genesis')
				->and('User', 'Friend')
                ->and('Robot', 'drake')
            ->when('Exec', 'repair '.$this->getRobotId('sedative').' _')
            ->then('Failure')
    ;}

    public function testInvalidNonRepairableRobot() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'mv 12,5')
			->when('Exec', 'mv 12,5')
			->when('Exec', 'mv 12,5')
            ->when('Exec', 'repair '.$this->getRobotId('sedative').' I')
            ->then('Failure')
    ;}

    public function testInvalidNonRepairableTargetRobot() {
        return $this
            ->given('Genesis')
                ->and('User', 'Friend')
                ->and('Robot', 'drake')
            ->when('Exec', 'mv 9,8')
			->when('Exec', 'mv 9,8')
            ->when('Exec', 'repair '.$this->getRobotId('fuel').' F')
            ->then('Failure')
	;}

    public function testInvalidLetterNotPunchedOut() {
        return $this
			->given('Genesis')
				->and('User', 'Friend')
                ->and('Robot', 'drake')
            ->when('Exec', 'repair '.$this->getRobotId('sedative').' E')
            ->then('Failure')
    ;}

    public function testInvalidLetterNotInRobotWord() {
        return $this
			->given('Genesis')
				->and('User', 'Friend')
                ->and('Robot', 'drake')
            ->when('Exec', 'repair '.$this->getRobotId('sedative').' G')
            ->then('Failure')
    ;}

    public function testInvalidLetterNotInDrops() {
        return $this
			->given('Genesis')
				->and('User', 'Friend')
                ->and('Robot', 'drake')
            ->when('Exec', 'fire '.$this->getRobotId('sedative').' S')
            ->when('Exec', 'repair '.$this->getRobotId('sedative').' S')
            ->then('Failure')
    ;}

    public function testInvalidNotInTheSameSector() {
        return $this
            ->given('Genesis')
                ->and('User', 'Friend')
                ->and('Robot', 'drake')
            ->when('Exec', 'mv 9,8')
            ->when('Exec', 'repair '.$this->getRobotId('sedative').' I')
            ->then('Failure')
	;}

    public function getRobotTestCommand() {
        return 'repair '.$this->getRobotId('sedative').' I';
    }

}
