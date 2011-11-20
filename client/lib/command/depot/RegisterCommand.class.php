<?php

require_once dirname(__FILE__).'/../BaseUserInterfaceCommand.class.php';

class RegisterCommand extends BaseUserInterfaceCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Register new user'
		) + parent::getParserConfig();
	}

	public function getArgumentConfigs() {
		return array(
			'username' => array(
				'description' => 'New user name'
			),
			'password' => array(
				'description' => 'A secret phrase used with conjunction with username for authentication'
			)
		);
	}
	
	public function execute($options, $arguments) {
		$this->postForm('user', 'user/create', $arguments);
	}
	
}
