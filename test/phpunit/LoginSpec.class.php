<?php

require_once __DIR__.'/../BaseSpec.class.php';

class LoginSpec extends BaseSpec {
	public function testNormal() {
		$this
			->given('Genesis')
			->when('Exec', 'login alice asdf')
			->then('Success')
	;}
	
	/* Borderline */

	public function testUserNotFound() {
		$this
			->given('Genesis')
			->when('Exec', 'login usernotfound password')
			->then('Failure')
	;}
	
	
	public function testWrongPassword() {
		$this
			->given('Genesis')
			->when('Exec', 'login alice wrongpassword')
			->then('Failure')
	;}
	
}
