<?php

require_once __DIR__.'/../BotBaseSpec.class.php';

class RiderOnTheStormSpec extends BotBaseSpec {
    public function setUp() {
        parent::setUp();
        return $this
            ->given('Genesis')
            ->and('User', 'RiderOnTheStormBotUser')
            ->and('Robot', 'storm')
    ;}

	public function testNormal() {
//        $this
//		    ->when('NightComesDown')
	;}

	/* Borderline */
    public function whenNightComesDown($times = 1) {
        return parent::whenNightComesDown($this->getBotId('RiderOnTheStorm'), $times);
    }

}
