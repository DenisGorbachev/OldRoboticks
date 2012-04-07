<?php

require_once __DIR__.'/../../RealmBaseSpec.class.php';

class RealmShowSpec extends RealmBaseSpec {
    public function testNormal() {
        return $this
            ->given('Genesis')
            ->given('User', 'Alice')
            ->given('Realm', 'Universe')
            ->when('Exec', 'realm:show')
            ->then('Success')
            ->then('Contains', 'Universe')
            ->then('Contains', 'DeathmatchRealmController')
            ->then('Contains', '400 sectors')
            ->then('Contains', '7 users')
            ->then('Contains', '54 active robots')
            ->then('Contains', '55 total robots')
            ->then('Contains', 'eliminate')
    ;}

    /* Borderline */

}
