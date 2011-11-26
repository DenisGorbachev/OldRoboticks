<?php

require_once __DIR__.'/../BaseSpec.class.php';

class ReportSpec extends BaseSpec {
	public function testRobots() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Exec', 'report '.$this->getRobotId('tea'))
			->then('Success')
				->and('Contains', ' 1 ') // enemy
                ->and('Contains', ' 2 ') // ally
                ->and('Contains', ' 3 ') // enemy+ally
                ->and('Contains', ' 4 ') // own
                ->and('Contains', ' 5 ') // own+enemy
                ->and('Contains', ' 6 ') // own+ally
                ->and('Contains', ' 7 ') // own+ally+enemy
                ->and('Contains', ' 3  -  1 ') // the second sector contains a neutral robot, which is marked as enemy

                ->and('Contains', ' 2  4 ') // Alice's robot is near her ally
	;}

	public function testRobotsAfterMove() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Exec', 'mv --relative '.$this->getRobotId('tea').' 3,0')
                ->and('Exec', 'report '.$this->getRobotId('tea'))
			->then('Success')
                ->and('Contains', ' 2  -  -  -  4 ') // Alice's robot is three sectors away from ally
	;}
	
	public function testLetters() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Exec', 'report --for letters '.$this->getRobotId('tea'))
			->then('Success')
				->and('HasDefaultCoordinatesWithMesh')
				->and('Contains', ' T ')
                ->and('Contains', ' E ')
                ->and('Contains', ' A ')
                ->and('Contains', ' D ')
                ->and('Contains', ' S ')
	;}
	
	public function testLettersReport() {
		
	;}
	
	
	public function testDrops($type = 'drops') {
		
	;}
	
	public function testDropsReport() {
		
	;}

	/* Borderline */

	public function testInvalidReportOther() {
		$this
			->given('Genesis')
				->and('User', 'Mob')
			->when('Exec', 'report '.$this->getRobotId('tea'))
			->then('Failure')
	;}	

}
