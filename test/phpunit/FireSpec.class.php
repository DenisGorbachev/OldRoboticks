<?php

require_once __DIR__.'/../RobotBaseSpec.class.php';

class FireSpec extends RobotBaseSpec {
    public function testNormal() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tear')
            ->when('Exec', 'fire 9,8 U')
            ->then('Success')
            ->when('Exec', 'fire 9,8 F')
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
            ->when('Exec', 'fire 9,8 T')
            ->then('Success')
            ->when('Exec', 'report --for robots')
            ->then('Contains', 'enemy   CAR_')
                ->given('User', 'Foe')
                ->given('Robot', 'cart')
            ->when('Exec', 'mv 9,9')
            ->then('Success')
    ;}

    public function testEmptySector() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tear')
            ->when('Exec', 'fire 10,10 U')
            ->then('Notice')
            ->then('Contains', '0 robots')
    ;}

    public function testCompleteDestruction() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tear')
            ->when('Exec', 'fire 9,8 U')
            ->when('Exec', 'fire 9,8 F')
            ->given('Robot', 'dirk')
            ->when('Exec', 'fire 9,8 E')
            ->when('Exec', 'fire 9,8 L')
            ->when('Exec', 'report --for robots')
            ->then('NotContains', $this->getRobotId('fuel'))
            ->when('Exec', 'report --for drops')
            ->then('Contains', '9,8     K X')
    ;}

    /* Borderline */

    public function testInvalidNonExistingSector() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'radio')
            ->when('Exec', 'fire 20,20 E')
            ->then('Failure')
    ;}

    public function testInvalidOutOfRangeTarget() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tear')
            ->when('Exec', 'fire 15,4 U')
            ->then('Failure')
            ->then('NotContains', 'PLUSH')
    ;}

    public function testInvalidNonFireableLetter() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tear')
            ->when('Exec', 'fire 9,8 L')
            ->then('Failure')
    ;}

    public function getRobotTestCommand() {
        return 'fire 9,8 U';
    }

}
