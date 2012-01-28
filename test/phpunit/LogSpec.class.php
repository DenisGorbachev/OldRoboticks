<?php

require_once __DIR__.'/../BaseSpec.class.php';

class LogSpec extends BaseSpec {
	public function testGlobalScope() {
		$this
            ->given('Genesis')
			->when('Exec', 'login -p wrongpassword nonexistinguser')
			->then('LogContains', '/all', 'Failure')
	;}

    public function testRealmScope() {
        $this
            ->given('Genesis')
            ->given('User', 'Alice')
            ->given('Realm', 'Universe')
            ->when('Exec', 'ls')
            ->then('LogContains', '/realm/1', 'Success')
    ;}

    public function testRobotScope() {
        $this
            ->given('Genesis')
            ->given('User', 'Alice')
            ->given('Realm', 'Universe')
            ->given('Robot', 'tea')
            ->when('Exec', 'mv 1,1')
            ->then('LogContains', '/robot/1', 'Success')
    ;}

}
