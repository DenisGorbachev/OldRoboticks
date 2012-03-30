<?php

require_once __DIR__.'/../../FunBaseSpec.class.php';

class FunWanderSpec extends FunBaseSpec {
    public $command = 'RK_OUTPUT_FORMAT=json rk report';
//    public $command = 'echo checkpoint';

    public function setUp() {
        parent::setUp();
        return $this
            ->given('Genesis')
            ->and('User', 'Alice')
            ->and('Realm', 'Universe')
            ->and('Robot', 'tea')
            ->when('Exec', 'mv 6,6')
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
            ->then('Contains', 'TEAR')
	;}

    public function testFullReset() {
        $this
            ->when('Exec', 'fun:wander --reset --steps 1 8,8 "rk ls"')
            ->then('Contains', '8,8')
            ->when('Exec', 'fun:wander --reset --steps 1 8,8 "rk ls"')
            ->then('Contains', '8,8')
	;}

    public function testStepping() {
        $this
            ->when('Exec', 'fun:wander --steps 4 8,8 "rk ls"')
            ->then('Contains', '8,19')
            ->when('Exec', 'fun:wander --steps 1 8,8 "rk ls"')
            ->then('Contains', 'TEETER')
            ->when('Exec', 'fun:wander --steps 2 8,8 "rk ls"')
            ->then('Contains', '19,19')
   	;}

    /**
     * The robot doesn't exactly move in squares if it encounters obstacles (it bounces for 1 sector). This behavior is normal.
     */
    public function testFullCycle() {
        $this
            ->when('Exec', 'fun:wander --steps 3 8,8 "'.$this->command.'"')
			->then('Contains', '8,8')
            ->when('Exec', 'fun:wander --steps 3 8,8 "'.$this->command.'"')
            ->then('Contains', '8,19')
            ->when('Exec', 'fun:wander --steps 3 8,8 "'.$this->command.'"')
            ->then('Contains', '19,19')
            ->when('Exec', 'fun:wander --steps 2 8,8 "'.$this->command.'"')
            ->then('Contains', '19,8')
            ->when('Exec', 'fun:wander --steps 3 8,8 "'.$this->command.'"')
            ->then('Contains', '19,2')
            ->when('Exec', 'fun:wander --steps 2 8,8 "'.$this->command.'"')
            ->then('Contains', '8,3')
            ->when('Exec', 'fun:wander --steps 2 8,8 "'.$this->command.'"')
            ->then('Contains', '2,3')
            ->when('Exec', 'fun:wander --steps 3 8,8 "'.$this->command.'"')
            ->then('Contains', '3,8')
            ->when('Exec', 'fun:wander --steps 3 8,8 "'.$this->command.'"')
            ->then('Contains', '3,19')
            ->when('Exec', 'fun:wander --steps 6 8,8 "'.$this->command.'"')
            ->then('NotContains', 'Success')
            ->then('ContainsAllSectors', 0, 0, 19, 19)
	;}

    public function testBorders() {
        $this
            ->when('Exec', 'fun:wander 19,19 "'.$this->command.'"')
            ->then('ContainsAllSectors', 0, 0, 19, 19)
    ;}

    public function testInvalidBase() {
        $this
            ->when('Exec', 'fun:wander 25,25 "'.$this->command.'"')
            ->then('Contains', 'Failure')
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
