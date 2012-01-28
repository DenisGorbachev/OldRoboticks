<?php

require_once __DIR__.'/../RealmBaseSpec.class.php';

class RealmEditSpec extends RealmBaseSpec {
    public function setUp() {
        parent::setUp();
        return $this
            ->given('Genesis')
            ->given('User', 'Gamemaster')
            ->given('Realm', 'Etherworld')
    ;}

	public function testNormal() {
		return $this
			->when('Exec', 'realm:edit name=Newname')
            ->then('Success')
            ->when('Exec', 'realm:show')
            ->then('Contains', 'Newname')
	;}

    public function testPassword() {
		return $this
			->when('Exec', 'realm:edit name=Newname password=newpassword')
            ->then('Success')
            ->given('User', 'Mob')
            ->when('Exec', 'realm:join --no-password '.$this->getRealmId('Etherworld'))
            ->then('Failure')
            ->when('Exec', 'realm:join -p newpassword '.$this->getRealmId('Etherworld'))
            ->then('Success')
	;}

    public function testOwnerId() {
        return $this
			->when('Exec', 'realm:edit owner_id=1')
            ->then('Success')
            ->when('Exec', 'realm:edit owner_id=1')
            ->then('Failure')
            ->given('User', 'Alice')
            ->when('Exec', 'realm:edit owner_id=1')
            ->then('Success')
    ;}

	/* Borderline */

    public function testInvalidNotOwner() {
        return $this
            ->given('User', 'Alice')
            ->when('Exec', 'realm:edit name=Newname')
            ->then('Failure')
    ;}

}
