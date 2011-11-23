<?php

require_once __DIR__.'/../BaseSpec.class.php';

class ScanSpec extends BaseSpec {
	public function testRobots() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Exec', 'scan '.$this->getRobotId('tea'))
			->then('Success')
				->and('HasCoordinatesWithMesh')
				->and('Contains', ' 1 ') // enemy
                ->and('Contains', ' 2 ') // ally
                ->and('Contains', ' 3 ') // enemy+ally
                ->and('Contains', ' 4 ') // own
                ->and('Contains', ' 5 ') // own+enemy
                ->and('Contains', ' 6 ') // own+ally
                ->and('Contains', ' 7 ') // own+ally+enemy
            ->markTestIncomplete('Test for neutral stance, or get rid of it')
	;}

	public function testRobotsAfterMove() {
		
	;}
	
	public function testRobotsReport() {
		
	;}

	public function testLetters() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Exec', 'scan --for letters '.$this->getRobotId('tea'))
			->then('Success')
				->and('HasCoordinatesWithMesh')
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

	public function testInvalidMapOther() {
		$this
			->given('Genesis')
				->and('User', 'Mob')
			->when('Exec', 'scan '.$this->getRobotId('tea'))
			->then('Failure')
	;}	

    public function thenHasCoordinatesWithMesh() {
        return $this
            ->and('Contains', '4,14')
            ->and('Contains', '14,14')
            ->and('Contains', '4,4')
            ->and('Contains', '14,4')
            ->and('Contains', '-  -  -  -  -  -  -  -  -  -  -')
    ;}

}
