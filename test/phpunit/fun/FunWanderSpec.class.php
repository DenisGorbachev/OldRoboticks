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
            ->when('Exec', 'fun:wander 9,9 "RK_OUTPUT_FORMAT=json rk report"')
//			->when('Exec', 'fun:wander "rk ls | grep -P \'^'.$this->getRobotId('tea').'\'"')
            ->then('ContainsAllSectors', 0, 0, 19, 19)
	;}

    public function testBase() {
        $this
			->when('Exec', 'fun:wander --steps 1 10,10 "rk ls"')
            ->then('Contains', '10,10')
	;}

    public function testBaseReset() {
        $this
			->when('Exec', 'fun:wander --steps 1 10,10 "rk ls"')
            ->then('Contains', '10,10')
            ->when('Exec', 'fun:wander --steps 1 8,8 "rk ls"')
            ->then('Contains', '8,8')
            ->when('Exec', 'fun:wander --steps 1 8,8 "rk ls"')
            ->then('Contains', '8,19')
	;}

    public function testStepping() {
        $this
            ->when('Exec', 'fun:wander --steps 3 8,8 "rk ls"')
            ->then('Contains', '19,19')
            ->when('Exec', 'fun:wander --steps 1 8,8 "rk ls"')
            ->then('Contains', '19,8')
            ->when('Exec', 'fun:wander --steps 3 8,8 "rk ls"')
            ->then('Contains', '0,0')
   	;}

    public function testBorders() {
        // 20x20 realm, `rk fun:wander 19,19`, ?
    ;}

    public function thenContainsAllSectors($blX, $blY, $trX, $trY, $message = '', $ignoreCase = false) {
        for ($x = $blX; $x <= $trX; $x++) {
            for ($y = $blY; $y <= $trY; $y++) {
                $this->assertContains('"x":"'.$x.'","y":"'.$y.'"', $this->world['output'], $message, $ignoreCase);
            }
        }
        return $this;
    }

}
