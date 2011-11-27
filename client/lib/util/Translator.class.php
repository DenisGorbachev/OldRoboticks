<?php

class Translator {
	public $text = '';
	public $arguments = array();
	
	public function __construct(array $message) {
		$this->text = $message['text'];
		$this->arguments = $message['arguments'];
	}
	
	public function translate() {
		return preg_replace_callback('/%[^%]+%/u', array($this, 'replace'), $this->text);
	}
	
	public function replace($matches) {
		$name = trim($matches[0], '%');
		return $this->arguments[$name];
	}
	
}	
