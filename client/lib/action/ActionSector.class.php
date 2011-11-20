<?php

class ActionSector extends ActionRegex {
	public function execute($value = false, $params = array()) {
		parent::execute($value, array(
			'regex' => '/^\d+,\d+$/',
			'message' => 'Option "%s" must be in form of two integers separated by a comma'
		));
    }
}
