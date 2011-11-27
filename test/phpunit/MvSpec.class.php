<?php

require_once __DIR__.'/../RobotBaseSpec.class.php';

class MvSpec extends RobotBaseSpec {
	public function testStand() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'mv 9,9')
			->then('Success')
				->and('Contains', '9,9')
	;}
	
	public function testShortStraightXMove() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'mv 8,9')
			->then('Success')
				->and('Contains', '8,9')
	;}

	public function testShortStraightYMove() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'mv 9,8')
			->then('Success')
				->and('Contains', '9,8')
	;}
	
	public function testShortDiagonalMove() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'mv 8,8')
			->then('Success')
				->and('Contains', '8,8')
	;}
	
	public function testLongStraightMove() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'mv 0,9')
			->then('Success')
				->and('Contains', '6,9')
	;}
	
	public function testLongDiagonalMove() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'mv 0,0')
			->then('Success')
				->and('Contains', '7,7')
	;}

	public function testRelativeMove() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'mv --relative 1,1')
			->then('Success')
				->and('Contains', '10,10')
	;}
	
	/* Borderline */

	public function testInvalidPositiveCoordinates() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'tea')
			->when('Exec', 'mv 100,100')
			->then('Failure')
	;}
	
	public function testInvalidImmobile() {
		$this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Robot', 'grunt')
			->when('Exec', 'mv 0,9')
			->then('Failure')
	;}

    public function getRobotTestCommand() {
        return 'mv 10,10';
    }

}
