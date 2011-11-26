<?php

require_once dirname(__FILE__).'/base/UserInterfaceCommand.class.php';

class LoginCommand extends UserInterfaceCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Authenticate yourself'
		) + parent::getParserConfig();
	}

	public function getArgumentConfigs() {
		return array(
			'username' => array(
				'description' => 'User name taken at registration'
			),
			'password' => array(
				'description' => 'A secret phrase used with conjunction with username for authentication'
			)
		);
	}
	
	public function execute($options, $arguments) {
		$this->get('user/login', $arguments);
	}
	
}
