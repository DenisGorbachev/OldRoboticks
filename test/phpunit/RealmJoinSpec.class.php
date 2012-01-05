<?php

require_once __DIR__.'/../RealmBaseSpec.class.php';

class RealmJoinSpec extends RealmBaseSpec {
    public function setUp() {
        return parent::setUp()
            ->given('Genesis')
            ->given('User', 'Friend')
    ;}

    public function testNormal() {
        return $this
            ->given('Realm', 'Lawn')
			->when('Exec', 'realm:join lawnpassword')
            ->then('Success')
	;}

    public function testWithoutPassword() {
        return $this
            ->given('Realm', 'Etherworld')
			->when('Exec', 'realm:join')
            ->then('Success')
	;}

    public function testJoinTwiceWithoutError() {
        return $this
            ->given('Realm', 'Lawn')
			->when('Exec', 'realm:join lawnpassword')
            ->then('Success')
            ->when('Exec', 'realm:join lawnpassword')
            ->then('Success')
	;}

	/* Borderline */

    public function testInvalidWrongPassword() {
        return $this
            ->given('Realm', 'Lawn')
			->when('Exec', 'realm:join wrongpassword')
            ->then('Failure')
	;}

}
