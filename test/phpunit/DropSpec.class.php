<?php

require_once __DIR__.'/../RobotBaseSpec.class.php';

class DropSpec extends RobotBaseSpec {
    public function testNormal() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
				->and('Realm', 'Universe')
                ->and('Robot', 'tea')
			->then('NotContains', '9,9     M')
			->when('Exec', 'drop M')
			->then('Success')
			->when('Exec', 'report --for drops')
			->then('Contains', '9,9     M')
	;}

    /* Borderline */

	public function testInvalidNonDroppableRobot() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
				->and('Realm', 'Universe')
                ->and('Robot', 'pear')
			->when('Exec', 'drop M')
			->then('Failure')
            ->when('Exec', 'report --for drops')
            ->then('NotContains', '11,6     M')
	;}

    public function testInvalidNonDroppableLetter() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
				->and('Realm', 'Universe')
                ->and('Robot', 'tea')
			->when('Exec', 'drop A')
			->then('Failure')
            ->when('Exec', 'report --for drops')
            ->then('NotContains', '9,9      A')
	;}

    public function testInvalidDropMoreThanOneLetter() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
				->and('Realm', 'Universe')
                ->and('Robot', 'teeter')
			->when('Exec', 'drop NZ')
			->then('Failure')
            ->when('Exec', 'report --for drops')
            ->then('NotContains', '4,19    B E A R N Z')
	;}

    public function testInvalidDisabledRobot() {
        $this
			->given('Genesis')
				->and('User', 'Alice')
				->and('Realm', 'Universe')
                ->and('Robot', 'sedative')
			->when('Exec', 'drop F')
			->then('Failure')
    ;}

    public function getRobotTestCommand() {
        return 'drop A';
    }
    
}
