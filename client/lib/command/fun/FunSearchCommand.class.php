<?php

require_once dirname(__FILE__) . '/../base/FunCommand.class.php';

class FunSearchCommand extends FunCommand {
	public function getParserConfig() {
		return array_merge(parent::getParserConfig(), array(
			'description' => 'Search for given letters'
		));
	}

	public function getOptionConfigs() {
		return array_merge(parent::getOptionConfigs(), array(
			'in' => array(
				'short_name' => '-i',
				'long_name' => '--in',
				'description' => 'The report to search in. Possible values are: robots, letters, drops',
				'action' => 'StoreString'
			)
		));
	}
	
	public function getArgumentConfigs() {
		return array_merge(parent::getArgumentConfigs(), array(
			'letters' => array(
				'description' => 'A string of letters to search for (example: ABC)'
			)
		));
	}
	

}
