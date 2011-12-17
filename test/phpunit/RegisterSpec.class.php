<?php

require_once __DIR__.'/../BaseSpec.class.php';

class RegisterSpec extends BaseSpec {
	public function testRegister() {
		return $this
			->given('Genesis')
			->when('Exec', 'register player asdf')
			->then('Success')
            ->given('User', 'player')
            ->then('Success')
	;}

	/* Borderline */

    public function testInvalidValidations() {
		return $this
			->given('Genesis')
			->when('Exec', 'register "player%" asdf')
			->then('Failure')
            ->when('Exec', 'register player "asdf&"')
			->then('Failure')
            ->when('Exec', 'register p asdf')
			->then('Failure')
            ->when('Exec', 'register player a')
			->then('Failure')
            ->when('Exec', 'register playplayplayplayplayplayplayplayplayplayplayplayplayplayplayplay66 asdf')
			->then('Failure')
            ->when('Exec', 'register player asdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdf66')
			->then('Failure')
	;}

	public function testInvalidSameUsername() {
		$this
    		->testRegister()
			->when('Exec', 'register player asdf')
			->then('Failure')
	;}
	
}
