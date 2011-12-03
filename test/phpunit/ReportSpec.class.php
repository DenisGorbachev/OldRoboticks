<?php

require_once __DIR__.'/../ScanBaseSpec.class.php';

class ReportSpec extends ScanBaseSpec {
	public function testRobots() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'report')
			->then('Success')
                ->and('Contains', '7   ally    STAKE     8,9     friend    STAKE     3')
                ->and('Contains', ' FUEL ')
				->and('NotContains', ' GRUNT ')
                ->and('NotContains', ' PLUSH ')
	;}

	public function testRobotsAfterMove() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'mv --relative 3,0')
                ->and('Exec', 'report')
			->then('Success')
                ->and('Contains', ' PLUSH ')
	;}
	
	public function testLetters() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'report --for letters')
			->then('Success')
				->and('Contains', '9,9     T')
                ->and('Contains', 'E')
                ->and('Contains', 'A')
                ->and('Contains', 'D')
                ->and('Contains', 'S')
	;}
	
	public function testDrops() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Robot', 'tea')
            ->when('Exec', 'report --for drops')
            ->then('Success')
                ->and('Contains', '9,8     K')
                ->and('Contains', 'H O Q Z J G I R N Q J D E T O O')
                ->and('NotContains', 'P')

	;}
	
	/* Borderline */

    public function getRobotTestCommand() {
        return 'report';
    }

}
