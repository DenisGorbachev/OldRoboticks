<?php

require_once __DIR__.'/../RobotBaseSpec.class.php';

class FireSpec extends RobotBaseSpec {
	// Number of letter to fire at is not implemented, as it would foster the player imagination to come up with hard-to-disable-robots
	
	public function testNormal() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
				->and('Realm', 'Universe')
                ->and('Robot', 'tear')
			->when('Exec', 'fire '.$this->getRobotId('fuel').' U')
			->then('Success')
			->when('Exec', 'fire '.$this->getRobotId('fuel').' F')
			->then('Success')
            ->when('Exec', 'report --for robots')
            ->then('Contains', 'enemy   __EL')
                ->given('User', 'Foe')
                ->given('Robot', 'fuel')
            ->when('Exec', 'mv 9,9')
            ->then('Failure')
	;}

	public function testWoundedButAliveRobot() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
				->and('Realm', 'Universe')
                ->and('Robot', 'seaside')
			->when('Exec', 'fire '.$this->getRobotId('cart').' T')
			->then('Success')
            ->when('Exec', 'report --for robots')
            ->then('Contains', 'enemy   CAR_')
                ->given('User', 'Foe')
                ->given('Robot', 'cart')
            ->when('Exec', 'mv 9,9')
            ->then('Success')
	;}

    public function testCompleteDestruction() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
				->and('Realm', 'Universe')
                ->and('Robot', 'tear')
			->when('Exec', 'fire '.$this->getRobotId('fuel').' U')
			->when('Exec', 'fire '.$this->getRobotId('fuel').' F')
			->given('Robot', 'dirk')
			->when('Exec', 'fire '.$this->getRobotId('fuel').' E')
			->when('Exec', 'fire '.$this->getRobotId('fuel').' L')
            ->when('Exec', 'report --for robots')
            ->then('NotContains', $this->getRobotId('fuel'))
            ->when('Exec', 'report --for drops')
            ->then('Contains', '9,8     K X')
    ;}

	/* Borderline */

    public function testInvalidNonExistingTarget() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tear')
            ->when('Exec', 'fire 111 A')
            ->then('Failure')
    ;}

    public function testInvalidOutOfRangeTarget() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tear')
            ->when('Exec', 'fire '.$this->getRobotId('plush').' U')
            ->then('Failure')
    ;}

    public function testInvalidNonExistingLetter() {
        return $this
			->given('Genesis')
				->and('User', 'Alice')
				->and('Realm', 'Universe')
                ->and('Robot', 'tear')
			->when('Exec', 'fire '.$this->getRobotId('fuel').' A')
			->then('Failure')
    ;}

    public function testInvalidNonFireableLetter() {
        return $this
			->given('Genesis')
				->and('User', 'Alice')
				->and('Realm', 'Universe')
                ->and('Robot', 'tear')
			->when('Exec', 'fire '.$this->getRobotId('fuel').' L')
			->then('Failure')
    ;}

    public function getRobotTestCommand() {
        return 'fire '.$this->getRobotId('fuel').' U';
    }

}
