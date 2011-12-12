<?php

require_once __DIR__.'/../BaseSpec.class.php';

class RealmSpec extends BaseSpec {
	public function testNormal() {
		return $this
			->given('Genesis')
				->and('User', 'Alice')
                ->and('Realm', 'Etherworld')
                ->and('Robot', 'meat')
			->when('Exec', 'report')
			->then('NotContains', 'TEA')
			->when('Exec', 'ls')
            ->then('NotContains', 'TEA')
	;}

	/* Borderline */

    public function testInvalidFireThroughRealms() {
        return $this
            ->given('Genesis')
                ->and('User', 'Alice')
                ->and('Realm', 'Etherworld')
                ->and('Robot', 'meat')
            ->when('Exec', 'fire 11 U')
            ->then('Failure')
    ;}

    public function testInvalidPlayOnInaccessibleRealm() {
        return $this
            ->given('Genesis')
                ->and('User', 'Friend')
                ->and('Realm', 'Etherworld')
            ->when('Exec', 'ls')
            ->then('Failure')
    ;}

}
