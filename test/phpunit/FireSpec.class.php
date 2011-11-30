<?php

require_once __DIR__.'/../RobotBaseSpec.class.php';

class FireSpec extends RobotBaseSpec {
	public function testNormal() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'fire 11 U')
			->then('Success')
            ->when('Exec', 'report --for robots')
            ->then('Contains', 'enemy   F_EL')
                ->given('User', 'Foe')
                ->given('Robot', 'fuel')
            ->when('Exec', 'mv 9,9')
            ->then('Failure')
	;}

	/* Borderline */

    public function testInvalidNonExistingRobot() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Robot', 'tea')
            ->when('Exec', 'fire 111 A')
            ->then('Failure')
    ;}

    public function testInvalidOutOfRangeRobot() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Robot', 'tea')
            ->when('Exec', 'fire 15 U')
            ->then('Failure')
    ;}

    public function testInvalidNonExistingLetter() {
        return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'fire 11 A')
			->then('Failure')
    ;}

    public function testInvalidNonFireableLetter() {
        return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'fire 11 L')
			->then('Failure')
    ;}

    public function getRobotTestCommand() {
        return 'fire 11 U';
    }

}
