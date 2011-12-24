<?php

require_once __DIR__.'/../BaseSpec.class.php';

class RealmCreateSpec extends BaseSpec {
	public function testNormal() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Exec', 'realm:create Afterlife asdf')
            ->then('Success')
			    ->and('Contains', 'Afterlife')
                ->and('Contains', 'Deathmatch')
            ->given('Realm', 'Afterlife')
            ->when('Exec', 'ls')
            ->then('Contains', 'TEA')
            ->given('Robot', 'justregistered') // TEA
            ->when('Exec', 'mv 1,1')
            ->then('Success')
	;}

    public function testType() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Exec', 'realm:create -c MapAndMoveTutorialRealmController Afterlife asdf')
            ->then('Success')
                ->and('Contains', 'MapAndMoveTutorial')
	;}

	/* Borderline */

    public function testInvalidSameName() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
            ->when('Exec', 'realm:create Afterlife asdf')
            ->when('Exec', 'realm:create Afterlife asdf')
            ->then('Failure')
    ;}

    public function testInvalidControllerClass() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
            ->when('Exec', 'realm:create -c NonExistingRealmController Afterlife asdf')
            ->then('Failure')
    ;}

}
