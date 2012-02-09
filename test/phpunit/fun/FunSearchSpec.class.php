<?php

require_once __DIR__.'/../../FunBaseSpec.class.php';

class FunSearchSpec extends FunBaseSpec {
    public function setUp() {
        parent::setUp();
        return $this
            ->given('Genesis')
            ->and('User', 'Alice')
            ->and('Realm', 'Universe')
            ->and('Robot', 'tea')
    ;}

    public function testNormal() {
        $this
			->when('Exec', 'fun:search "U|F|B"')
            ->then('Contains', 'FINGER')
            ->then('Contains', 'FUEL')
            ->then('Contains', 'GRUNT')
            ->then('Contains', 'MOUSE')
            ->then('Contains', 'MUSHROOM')
            ->then('Contains', 'PLUSH')
            ->then('Contains', 'PUSH')
            ->then('Contains', 'RUSH')
	;}

    public function testStopWhenFound() {
        $this
			->when('Exec', 'fun:search --stop-when-found "U|F|B"')
            ->then('Contains', 'FUEL')
            ->then('NotContains', 'PLUSH')
	;}

    public function testForLetters() {

    ;}

    public function testForDrops() {

    ;}

    public function testStepping() {
        $this
            ->when('Exec', 'fun:search --steps 1 "U|F|B"')
            ->then('Contains', 'FUEL')
            ->then('NotContains', 'PLUSH')
   	;}

}
