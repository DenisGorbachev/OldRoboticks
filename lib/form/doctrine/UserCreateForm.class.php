<?php

class UserCreateForm extends UserForm {
	public function configure() {
		parent::configure();
		
		$this->useFields(array('username', 'password'));
	}
	
	public function getSuccessText() {
		return
            'registered user %user%, created robot %robot% at %sector%. '
            .PHP_EOL.'Select it using `rk select %robot_id%`.'
            .PHP_EOL.'Look around using `rk map` or `rk report`.'
            .PHP_EOL.'Move it using `rk mv X,Y`.'
            .PHP_EOL.'List all available commands using `rk`.'
	;}

	public function getSuccessArguments() {
		return array(
            'id' => (string)$this->object->getId(),
			'user' => (string)$this->object,
            'robot' => (string)$this->object->Robots[0],
            'sector' => (string)$this->object->Robots[0]->getSector(),
            'robot_id' => (string)$this->object->Robots[0]->getId(),
		);
	}
	
}
