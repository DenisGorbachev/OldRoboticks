<?php

require_once __DIR__.'/../BaseSpec.class.php';

class StatusSpec extends BaseSpec {
    public function testDefault() {
        $this
            ->given('Genesis')
            ->when('Exec', 'status')
            ->then('Contains', 'undefined')
            ->then('Contains', 'no')
    ;}

    public function testChange() {
        $this
            ->given('Genesis')
            ->given('User', 'Alice')
            ->given('Realm', 'Universe')
            ->when('Exec', 'status')
            ->then('Contains', '1')
            ->then('Contains', 'undefined')
            ->then('Contains', 'yes')
    ;}

}
