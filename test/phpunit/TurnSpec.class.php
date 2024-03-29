<?php

require_once __DIR__.'/../BaseSpec.class.php';

/**
 * @group time_consuming
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
                ->and('Realm', 'Universe')
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
                ->and('Realm', 'Universe')
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
                ->and('Realm', 'Universe')
                ->and('Robot', 'tea')
            ->when('Exec', 'mv 1,1')
            ->then('Success')
            ->when('Exec', 'mv 1,1')
            ->then('PleaseWait')
            ->then('Contains', 'Success') // Command waits until it can be completed
    ;}

    public function testInvalidSprayFire() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tear')
            ->when('Exec', 'fire 15,4 U')
            ->then('Failure')
            ->when('Exec', 'fire 9,8 U')
            ->then('PleaseWait')
    ;}

    public function tearDown() {
        $this->setDebug(true);
        parent::tearDown();
    }

}
