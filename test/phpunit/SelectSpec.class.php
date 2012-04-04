<?php

require_once __DIR__.'/../BaseSpec.class.php';

class SelectSpec extends BaseSpec {
    public function testNormal() {
        return $this
            ->given('Genesis')
            ->when('Exec', 'select 1')
            ->then('Success')
    ;}

    /* Borderline */

    public function testInvalidArgumentsRobotId() {
        return $this
            ->given('Genesis')
            ->when('Exec', 'select asdf')
            ->then('Contains', 'Invalid argument')
    ;}

}
