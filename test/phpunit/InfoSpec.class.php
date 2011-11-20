<?php

require_once __DIR__.'/../BaseSpec.class.php';

class InfoSpec extends BaseSpec {
	public function testBare() {
		$this
			->when('Exec', '')
			->then('Contains', 'usage')
				->and('Contains', 'info')
	;}

	public function testVersion() {
		$this
			->when('Exec', '--version')
			->then('Contains', 'version')
	;}
	
	public function testHelp() {
		$this
			->when('Exec', 'mv --help')
			->then('Contains', 'Move')
	;}
	
}
