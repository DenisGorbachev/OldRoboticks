<?php

require_once __DIR__.'/../../BaseSpec.class.php';

/**
 * @group time_consuming
 */
class RealmCreateSpec extends BaseSpec {
    public function testNormal() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->addRealm('Afterlife')
                ->addRobot('tea_in_Afterlife')
            ->when('Exec', 'realm:create -p asdf Afterlife')
            ->then('Contains', 'Success')
                ->and('Contains', 'Afterlife')
                ->and('Contains', 'Deathmatch')
                ->and('Contains', 'TEA')
            ->given('Realm', 'Afterlife')
            ->when('Exec', 'ls')
            ->then('Contains', 'TEA')
            ->given('Robot', 'tea_in_Afterlife')
            ->when('Exec', 'mv 1,1')
            ->then('Success')
    ;}

    public function testWithoutPassword() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->addRealm('Afterlife')
            ->when('Exec', 'realm:create --no-password Afterlife')
            ->then('Contains', 'Success')
    ;}

    public function testType() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->addRealm('Afterlife')
            ->when('Exec', 'realm:create -c TutorialRealmController -p asdf Afterlife')
            ->then('Contains', 'Success')
                ->and('Contains', 'Tutorial')
    ;}

    /* Borderline */

    public function testInvalidSameName() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->addRealm('Afterlife')
            ->when('Exec', 'realm:create -p asdf Afterlife')
            ->when('Exec', 'realm:create -p asdf Afterlife')
            ->then('Contains', 'Failure')
    ;}

    public function testInvalidControllerClass() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->addRealm('Afterlife')
            ->when('Exec', 'realm:create -c NonExistingRealmController -p asdf Afterlife')
            ->then('Contains', 'Failure')
    ;}

}
