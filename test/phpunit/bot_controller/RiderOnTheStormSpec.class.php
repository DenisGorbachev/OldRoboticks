<?php

require_once __DIR__.'/../../BotBaseSpec.class.php';

/**
 * @group bot_controller
 */
class RiderOnTheStormSpec extends BotBaseSpec {
    public function __construct() {
        parent::__construct();
        $this->selectBot('RiderOnTheStorm');
    }

    public function setUp() {
        parent::setUp();
        return $this
            ->given('Genesis')
            ->given('User', 'RiderOnTheStormBotUser')
            ->given('Robot', 'storm')
    ;}

    public function testNormal() {
        $this
            ->when('MyNightComesDown', 1)
            ->then('LogContains', '/all', '4,16')
            ->when('MyNightComesDown', 3)
            ->then('LogContains', '/all', '16,16')
            ->when('MyNightComesDown', 3)
            ->then('LogContains', '/all', '16,4')
            ->when('MyNightComesDown', 3)
            ->then('LogContains', '/all', '4,4')
            ->when('MyNightComesDown', 3)
            ->then('LogContains', '/all', '4,16')
    ;}

    /* Borderline */

}
