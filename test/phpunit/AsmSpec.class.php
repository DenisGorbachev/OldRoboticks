<?php

require_once __DIR__.'/../RobotBaseSpec.class.php';

class AsmSpec extends RobotBaseSpec {
	public function testNormal() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'mv 10,12')
                ->and('Exec', 'asm BABY')
			->then('Success')
            ->when('Exec', 'report --for robots')
            ->then('Contains', 'own     BABY')
            ->when('Exec', 'report --for drops')
            ->then('NotContains', 'B')
            ->when('SelectRobotByName', 'BABY')
                ->and('Exec', 'mv 9,9')
            ->then('Success')
	;}

	/* Borderline */

    public function testInvalidNotEnoughDrops() {

	;}

    public function testInvalidNotAWord() {

	;}

    public function getRobotTestCommand() {
        return 'asm BABY';
    }

}
