<?php

require_once __DIR__.'/../BaseSpec.class.php';

/**
 * @group timing
 */
class TurnSpec extends BaseSpec {
    public function setUp() {
        parent::setUp();
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
                ->and('Robot', 'finger1')
			->when('Exec', 'mv 10,12')
            ->then('Success')
            ->when('Wait', 61)
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

    public function testInvalidSprayFire() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Robot', 'tear')
            ->when('Exec', 'fire '.$this->getRobotId('plush').' U')
            ->then('Failure')
            ->when('Exec', 'fire '.$this->getRobotId('fuel').' U')
            ->then('Failure')
    ;}

    public function tearDown() {
        $this->setDebug(true);
        parent::tearDown();
    }

}
