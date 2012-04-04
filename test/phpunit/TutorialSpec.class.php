<?php

require_once __DIR__.'/../BaseSpec.class.php';

class TutorialSpec extends BaseSpec {
    public function testMapAndMove() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
            ->when('Exec', 'tutorial')
            ->then('Success')
            ->markTestIncomplete()
//            ->then('Contains', 'created')
//                ->and('Contains', 'mail')
//            ->when('Exec', 'receive')
//            ->then('Contains', 'realm')
//            ->given('Realm', 'New')
//            ->when('Exec', 'receive --realm')
//            ->then('Contains', 'move')
//            ->given('Robot', 'justregistered') # TEA
//            ->when('Exec', 'mv 5,5')
//            ->then('Success')
//                ->and('Contains', 'mail')
//            ->when('Exec', 'receive --realm')
//            ->then('Contains', 'move')
    ;}

    /* Borderline */

    public function testMultipleTutorialsAttempt() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
            ->when('Exec', 'tutorial')
            ->when('Exec', 'tutorial')
            ->then('Contains', 'Failure')
    ;}

}
