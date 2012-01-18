<?php

class Translator {
	public $text = '';
	public $arguments = array();
	
	public function __construct(array $message) {
		$this->text = $message['text'];
        if (isset($message['arguments'])) {
            $this->arguments = $message['arguments'];
        }
	}

	public function translate() {
		return preg_replace_callback('/%[^%]+%/u', array($this, 'replace'), $this->text);
	}
	
	public function replace($matches) {
		$name = trim($matches[0], '%');
        $argument = $this->arguments[$name];
        if (is_array($argument)) {
            $string = '';
            foreach ($argument as $li) {
                $string .= PHP_EOL.' - '.__($li);
            }
            return $string;
        } else {
            return $argument;
        }
	}
	
}	
