<?php

require_once dirname(__FILE__).'/base/PasswordCommand.class.php';

class LoginCommand extends PasswordCommand {
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
		);
	}
	
	public function execute($options, $arguments) {
		return $this->get('user/login', array_merge($options, $arguments));
	}
	
}
