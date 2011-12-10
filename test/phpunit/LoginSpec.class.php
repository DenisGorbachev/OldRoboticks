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

    public function testInvalidActionWithoutAuthentication() {
        $this
            ->given('Genesis')
                ->and('Realm', 'Universe')
                ->and('Robot', 'tea')
            ->when('Exec', 'mv 10,10')
            ->then('Failure')
    ;}

	public function testInvalidUserNotFound() {
		$this
			->given('Genesis')
			->when('Exec', 'login usernotfound password')
			->then('Failure')
	;}
	
	
	public function testInvalidWrongPassword() {
		$this
			->given('Genesis')
			->when('Exec', 'login alice wrongpassword')
			->then('Failure')
	;}
	
}
