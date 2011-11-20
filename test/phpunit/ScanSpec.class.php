<?php

require_once __DIR__.'/../BaseSpec.class.php';

class ScanSpec extends BaseSpec {
	public function testRobots() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Exec', 'scan '.$this->getRobotId('tea'))
			->then('Success')
				->and('Contains', '4,14')
				->and('Contains', '9,14')
				->and('Contains', '14,14')
				
				->and('Contains', '4,9')
				->and('Contains', '14,9')
				
				->and('Contains', '4,4')
				->and('Contains', '9,4')
				->and('Contains', '14,4')
				
				->and('Contains', '-----------') // ___________
				->and('Contains', 'X')
	;}

	public function testRobotsAfterMove() {
		
	;}
	
	public function testRobotsReport() {
		
	;}

	public function testLetters($type = 'letters') {
		
	;}
	
	public function testLettersShortArgument() {
		return $this->testLetters('l');
	;}
	
	public function testLettersReport() {
		
	;}
	
	
	public function testDrops($type = 'drops') {
		
	;}
	
	public function testDropsShortArgument() {
		return $this->testDrops('d');
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
	
}
