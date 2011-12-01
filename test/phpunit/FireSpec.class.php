<?php

require_once __DIR__.'/../RobotBaseSpec.class.php';

class FireSpec extends RobotBaseSpec {
	// Number of letter to fire at is not implemented, as it would foster the player imagination to come up with hard-to-disable-robots
	
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

	public function testWoundedButAliveRobot() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'sea')
			->when('Exec', 'fire 17 T')
			->then('Success')
            ->when('Exec', 'report --for robots')
            ->then('Contains', 'enemy   CAR_')
                ->given('User', 'Foe')
                ->given('Robot', 'cart')
            ->when('Exec', 'mv 9,9')
            ->then('Success')
	;}

	/* Borderline */

    public function testInvalidNonExistingEnemy() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Robot', 'tea')
            ->when('Exec', 'fire 111 A')
            ->then('Failure')
    ;}

    public function testInvalidOutOfRangeEnemy() {
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
