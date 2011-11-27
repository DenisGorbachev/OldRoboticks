<?php

require_once __DIR__.'/../BaseSpec.class.php';

class AssembleSpec extends BaseSpec {
	public function testNormal() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Exec', 'mv '.$this->getRobotId('tea').' 10,12')
                ->and('Exec', 'asm '.$this->getRobotId('tea').' BABY')
			->then('Success')
            ->when('Exec', 'report --for robots '.$this->getRobotId('tea'))
            ->then('Contains', 'own     BABY')
            ->when('Exec', 'report --for drops '.$this->getRobotId('tea'))
            ->then('NotContains', 'B')
            ->when('StoreRobotIdByName', 'BABY')
                ->and('Exec', 'mv '.$this->getStoredRobotId().' 9,9')
            ->then('Success')
	;}

	/* Borderline */

    public function testInvalidNotOwnRobot() {

	;}

    public function testInvalidArgumentsRobotId() {

	;}

    public function testInvalidNotEnoughDrops() {

	;}

    public function testInvalidNotAWord() {

	;}
    
}
