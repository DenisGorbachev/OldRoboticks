<?php

require_once __DIR__.'/../RobotBaseSpec.class.php';

class DisasmSpec extends RobotBaseSpec {
	public function testNormal() {
		return $this
			->given('Genesis')
				->and('User', 'Friend')
				->and('Realm', 'Universe')
                ->and('Robot', 'drake')
            ->when('Exec', 'disasm '.$this->getRobotId('sedative'))
			->then('Success')
            ->when('Exec', 'report --for drops')
            ->then('Contains', '12,5    B E A R I S E D A T V E F')
            ->when('Exec', 'report --for robots')
            ->then('NotContains', 'SEDATIVE')
	;}

    public function testDisassembleOwnActiveRobot() {
        return $this
			->given('Genesis')
				->and('User', 'Alice')
				->and('Realm', 'Universe')
                ->and('Robot', 'dirk')
            ->when('Exec', 'disasm '.$this->getRobotId('tea'))
			->then('Success')
    ;}

	/* Borderline */

    public function testInvalidNonDisassembliveRobot() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tea')
			->when('Exec', 'mv 12,5')
			->when('Exec', 'mv 12,5')
			->when('Exec', 'mv 12,5')
            ->when('Exec', 'disasm '.$this->getRobotId('sedative'))
            ->then('Failure')
    ;}

    public function testInvalidNonDisassembliveTargetRobot() {
        return $this
            ->given('Genesis')
                ->and('User', 'Friend')
                ->and('Realm', 'Universe')
                ->and('Robot', 'drake')
            ->when('Exec', 'mv 9,8')
			->when('Exec', 'mv 9,8')
            ->when('Exec', 'disasm '.$this->getRobotId('fuel'))
            ->then('Failure')
	;}

    public function testInvalidNotInTheSameSector() {
        return $this
            ->given('Genesis')
                ->and('User', 'Friend')
                ->and('Realm', 'Universe')
                ->and('Robot', 'drake')
            ->when('Exec', 'mv 9,8')
            ->when('Exec', 'disasm '.$this->getRobotId('sedative'))
            ->then('Failure')
	;}

    public function getRobotTestCommand() {
        return 'disasm '.$this->getRobotId('sedative');
    }

}
