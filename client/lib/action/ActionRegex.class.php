<?php

class ActionRegex extends Console_CommandLine_Action {
	public function execute($value = false, $params = array()) {
		if (!preg_match($params['regex'], $value)) {
			throw new Exception(sprintf(
				$params['message'],
				$this->option->name,
				$params['regex']
			));
		}
		$this->setResult($value);
	}
}
