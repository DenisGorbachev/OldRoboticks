<?php

class rsException extends sfException {
	public $text;
	public $arguments;
	
	public function __construct($text, $arguments = array()) {
		$this->text = $text;
		$this->arguments = $arguments;
		parent::__construct($text);
	}

	public function getText() {
		return $this->text;
	}
	
	public function getArguments() {
		return $this->arguments;
	}
	
}
