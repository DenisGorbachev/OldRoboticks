<?php

class UserCreateForm extends UserForm {
	public function configure() {
		parent::configure();
		
		$this->useFields(array('username', 'password'));
	}
	
	public function getSuccessText() {
		return
            'registered user %user%.'
            .PHP_EOL.'If you play Roboticks for the first time, begin the tutorial by typing `rk begin`.'
	;}

	public function getSuccessArguments() {
		return array(
			'user' => (string)$this->object,
		);
	}
	
}
