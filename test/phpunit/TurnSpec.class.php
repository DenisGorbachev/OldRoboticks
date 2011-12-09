<?php

require_once __DIR__.'/../BaseSpec.class.php';

class TurnSpec extends BaseSpec {
    public function setUp() {
        $this->setDebug(false);
    }

	public function testNormal() {
		return $this
			->given('Genesis')
				->and('User', 'Stranger')
                ->and('Robot', 'mouse')
			->when('Exec', 'mv 10,12')
            ->then('Success')
            ->when('Wait', 2)
            ->when('Exec', 'mv 10,12')
			->then('Success')
	;}

    public function testLimit() {
		return $this
			->given('Genesis')
				->and('User', 'Manipulator')
                ->and('Robot', 'legion')
			->when('Exec', 'mv 10,12')
            ->then('Success')
            ->when('Wait', 60)
            ->when('Exec', 'mv 10,12')
            ->then('Success')
	;}

	/* Borderline */

    public function testInvalidPrematureAction() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Robot', 'tea')
            ->when('Exec', 'mv 10,12')
            ->then('Success')
            ->when('Exec', 'mv 10,12')
            ->then('Failure')
    ;}

    public function tearDown() {
        $this->setDebug(true);
    }

}
