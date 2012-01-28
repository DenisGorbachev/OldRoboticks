<?php

require_once __DIR__.'/../BaseSpec.class.php';

class RegisterSpec extends BaseSpec {
	public function testRegister() {
		return $this
			->given('Genesis')
			->when('Exec', 'register -p asdf player')
			->then('Success')
            ->given('User', 'player')
            ->then('Success')
	;}

	/* Borderline */

    public function testInvalidValidations() {
		return $this
			->given('Genesis')
			->when('Exec', 'register -p asdf "player%"')
			->then('Failure')
            ->when('Exec', 'register -p "asdf&" player')
			->then('Failure')
            ->when('Exec', 'register -p asdf p')
			->then('Failure')
            ->when('Exec', 'register -p a player')
			->then('Failure')
            ->when('Exec', 'register -p asdf playplayplayplayplayplayplayplayplayplayplayplayplayplayplayplay66')
			->then('Failure')
            ->when('Exec', 'register -p asdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdf66 player')
			->then('Failure')
	;}

	public function testInvalidSameUsername() {
		$this
    		->testRegister()
			->when('Exec', 'register -p asdf player')
			->then('Failure')
	;}
	
}
