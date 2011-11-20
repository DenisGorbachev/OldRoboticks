<?php

require_once __DIR__.'/../BaseSpec.class.php';

class RegisterSpec extends BaseSpec {
	public function testRegister() {
		return $this
			->given('Genesis')
			->when('Exec', 'register player asdf')
			->then('Success')
	;}

	public function testRegisterAndLogin() {
		$this
		->testRegister()
			->given('User', 'player')
			->then('Success')
	;}
	
	
	/* Borderline */

	public function testMultipleRegistration() {
		$this
		->testRegister()
			->when('Exec', 'register player asdf')
			->then('Failure')
	;}
	
}
