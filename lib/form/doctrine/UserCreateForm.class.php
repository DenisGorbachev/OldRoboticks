<?php

class UserCreateForm extends UserForm {
	public function configure() {
		parent::configure();
		
		$this->useFields(array('username', 'password'));
	}
	
	public function getSuccessText() {
		return 'registered user %user%. You can start playing by requesting your first robotick.';
	}

	public function getSuccessArguments() {
		return array(
			'user' => (string)$this->object
		);
	}
	
}
