<?php

require_once __DIR__.'/../RobotBaseSpec.class.php';

class ExtractSpec extends RobotBaseSpec {
    public function testNormal() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tea')
            ->when('Exec', 'extract')
            ->then('Success')
            ->when('Exec', 'report --for drops')
            ->then('Contains', '9,9     T')
            ->when('Exec', 'extract')
            ->then('Success')
            ->when('Exec', 'report --for drops')
            ->then('Contains', '9,9     T T')
    ;}

    /* Borderline */

    public function testInvalidNonExtractiveRobot() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'grunt')
            ->when('Exec', 'extract')
            ->then('Failure')
            ->when('Exec', 'report --for drops')
            ->then('NotContains', '4,19     T')
    ;}

    public function testInvalidNonExtractiveSector() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tea')
            ->when('Exec', 'mv --relative 1,0')
            ->when('Exec', 'extract')
            ->then('Failure')
            ->when('Exec', 'report --for drops')
            ->then('NotContains', '10,9     T')
    ;}

    public function getRobotTestCommand() {
        return 'extract';
    }
    
}
