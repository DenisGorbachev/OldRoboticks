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
	;}

    public function testType() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
			->when('Exec', 'realm:create -c MapAndMoveTutorialRealmController Afterlife asdf')
            ->then('Success')
                ->and('Contains', 'MapAndMoveTutorial')
	;}

    public function testPlayAfterCreate() {
        
    }

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
