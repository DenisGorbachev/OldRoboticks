<?php

require_once __DIR__.'/../BaseSpec.class.php';

class MvSpec extends BaseSpec {
	public function testStand() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Moves', $this->getRobotId('tea').' 9,9')
			->then('Success')
				->and('Contains', '9,9')
	;}
	
	public function testShortStraightXMove() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Moves', $this->getRobotId('tea').' 8,9')
			->then('Success')
				->and('Contains', '8,9')
	;}

	public function testShortStraightYMove() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Moves', $this->getRobotId('tea').' 9,8')
			->then('Success')
				->and('Contains', '9,8')
	;}
	
	public function testShortDiagonalMove() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Moves', $this->getRobotId('tea').' 8,8')
			->then('Success')
				->and('Contains', '8,8')
	;}
	
	public function testLongStraightMove() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Moves', $this->getRobotId('tea').' 0,9')
			->then('Success')
				->and('Contains', '6,9')
	;}
	
	public function testLongDiagonalMove() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Moves', $this->getRobotId('tea').' 0,0')
			->then('Success')
				->and('Contains', '7,7')
	;}

	public function testRelativeMove() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Moves', '-r '.$this->getRobotId('tea').' 1,1')
			->then('Success')
				->and('Contains', '10,10')
	;}
	
	/* Borderline */

    public function testInvalidNotOwnRobot() {
        $this
            ->given('Genesis')
                ->and('User', 'Mob')
            ->when('Moves', $this->getRobotId('tea').' 0,9')
            ->then('Failure')
    ;}

	public function testInvalidArgumentsRobotId() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Moves', '111 0,0')
			->then('Failure')
	;}
	
	public function testInvalidPositiveCoordinates() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Moves', $this->getRobotId('tea').' 100,100')
			->then('Failure')
	;}
	
	public function testInvalidImmobile() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Moves', $this->getRobotId('grunt').' 0,9')
			->then('Failure')
	;}
	
}
