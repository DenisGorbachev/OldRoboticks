<?php

require_once __DIR__.'/../../FunBaseSpec.class.php';

/**
 * @group fun
 */
class FunMvSpec extends FunBaseSpec {
    public function testNormal() {
        $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tea')
            ->when('Exec', 'fun:mv 0,0')
            ->then('Success')
            ->then('Contains', '3,3')
            ->then('Contains', '0,0')
    ;}

    public function testStepping() {
           $this
               ->given('Genesis')
                   ->and('User', 'Alice')
                   ->and('Realm', 'Universe')
               ->and('Robot', 'tea')
               ->when('Exec', 'fun:mv --steps 1 0,0')
            ->then('Contains', '3,3')
            ->then('NotContains', '0,0')
            ->when('Exec', 'fun:mv --steps 1 0,0')
            ->then('Contains', '0,0')
            ->then('NotContains', '3,3')
       ;}

}
