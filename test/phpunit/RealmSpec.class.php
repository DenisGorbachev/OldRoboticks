<?php

require_once __DIR__.'/../BaseSpec.class.php';

class RealmSpec extends BaseSpec {
	public function testNormal() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Realm', 'Underworld')
                ->and('Robot', 'meat')
			->when('Exec', 'report')
            ->then('Success')
//            ->and('NotContains', 'TEA') // TODO: place "MEAT" robot near "TEA"
			->when('Exec', 'ls')
            ->then('NotContains', 'TEA')
	;}

	/* Borderline */

    public function testInvalidFireThroughRealms() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Robot', 'meat')
            ->when('Exec', 'fire 11 U')
            ->then('Failure')
    ;}

}
