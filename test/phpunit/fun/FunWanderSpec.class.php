<?php

require_once __DIR__.'/../../FunBaseSpec.class.php';

class FunWanderSpec extends FunBaseSpec {
    public function setUp() {
        parent::setUp();
        return $this
            ->given('Genesis')
            ->and('User', 'Alice')
            ->and('Realm', 'Universe')
            ->and('Robot', 'tea')
            ->when('Exec', 'mv 6,6')
    ;}

    public function testNormal() {
        $this
            ->when('Exec', 'fun:wander "RK_OUTPUT_FORMAT=json rk report"')
//			->when('Exec', 'fun:wander "rk ls | grep -P \'^'.$this->getRobotId('tea').'\'"')
            ->then('ContainsAllSectors', 0, 0, 19, 19)
	;}

    public function testBase() {
        $this
			->when('Exec', 'fun:wander --steps 1 --base 10,10 "rk ls"')
            ->then('Contains', '10,10')
	;}

    public function testBaseReset() {
        $this
			->when('Exec', 'fun:wander --steps 1 --base 10,10 "rk ls"')
            ->then('Contains', '10,10')
            ->when('Exec', 'fun:wander --steps 1 --base 8,8 "rk ls"')
            ->then('Contains', '8,8')
            ->when('Exec', 'fun:wander --steps 1 --base 8,8 "rk ls"')
            ->then('Contains', '8,19')
	;}

    public function testStepping() {
        $this
            ->when('Exec', 'fun:wander --steps 2 "rk ls"')
            ->then('Contains', '10,10')
            ->when('Exec', 'fun:wander --steps 1 "rk ls"')
            ->then('Contains', '10,10')
            ->when('Exec', 'fun:wander --steps 3 "rk ls"')
            ->then('Contains', '10,10')
   	;}

    public function testBorders() {

    ;}

}
