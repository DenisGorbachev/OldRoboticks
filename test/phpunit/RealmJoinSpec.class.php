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
			->when('Exec', 'realm:join '.$this->getRealmId('Lawn').' lawnpassword')
            ->then('Success')
	;}

    public function testWithoutPassword() {
        return $this
			->when('Exec', 'realm:join '.$this->getRealmId('Etherworld'))
            ->then('Success')
	;}

    public function testJoinTwiceWithoutError() {
        return $this
			->when('Exec', 'realm:join '.$this->getRealmId('Lawn').' lawnpassword')
            ->then('Success')
            ->when('Exec', 'realm:join '.$this->getRealmId('Lawn').' lawnpassword')
            ->then('Success')
	;}

	/* Borderline */

    public function testInvalidWrongPassword() {
        return $this
			->when('Exec', 'realm:join '.$this->getRealmId('Lawn').' wrongpassword')
            ->then('Failure')
	;}

}
