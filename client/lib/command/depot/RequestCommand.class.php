<?php

require_once dirname(__FILE__).'/../BaseServerCommand.class.php';

class RequestCommand extends BaseServerCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Request playable robots from game server'
		) + parent::getParserConfig();
	}

	public function getArgumentConfigs() {
		return array(
			'item' => array(
				'description' => 'What to request. Possible values are: robot'
			)
		);
	}
	
	public function execute($options, $arguments) {
		$this->get('user/request', $arguments);
	}
	
}
