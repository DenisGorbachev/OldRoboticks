<?php

require_once __DIR__.'/../BaseSpec.class.php';

class CyclicSpec extends BaseSpec {
	public function testHarvester() {
		// Cyclic tasks idea. Harvester goes to the point A, extracts the letter, returns to the point B, drops it.
		$this->markTestIncomplete()
//		$this
//			->given('Genesis')
//				->and('User', 'Alice')
//			->when('Moves', 'Poet', '9,9')
//			->then('Success')
	;}
	
}
