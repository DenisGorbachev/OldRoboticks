<?php

require_once 'Console/CommandLine.php';
require_once 'Console/CommandLine/Action.php';
require_once_dir(LIBDIR.'/action');

abstract class BaseCommand {
	public $options = array();
	public $arguments = array();
	
	public function setOptions($options) {
		$this->options = $options;
		return $this;
	}
	
	public function getOptions() {
		return $this->options;
	}

	public function setOption($option, $value) {
		$this[$option] = $value;
	}
	
	public function getOption($option) {
		return $this[$option];
	}

	public function setArguments($arguments) {
		$this->arguments = $arguments;
		return $this;
	}
	
	public function getArguments() {
		return $this->arguments;
	}
	
	public function setArgument($argument, $value) {
		$this->arguments[$argument] = $value;
	}
	
	public function getArgument($argument) {
		return $this->arguments[$argument];
	}
	
	public function getOptionConfigs() {
		return array();
	}
	
	public function getArgumentConfigs() {
		return array();
	}
	
	public function getParserConfig() {
		return array(
			'add_version_option' => false
		);
	}
	
	public function parse() {
		$parser = new Console_CommandLine($this->getParserConfig());
		foreach ($this->getOptionConfigs() as $name=>$config) {
			$parser->addOption($name, $config);
		}
		foreach ($this->getArgumentConfigs() as $name=>$config) {
			$parser->addArgument($name, $config);
		}
		$args = $_SERVER['argv'];
		unset($args[1]);
		$result = $parser->parse(count($args), $args);
		$this->setOptions($result->options);
		$this->setArguments($result->args);
	}
	
	public function run() {
		return $this->execute($this->getOptions(), $this->getArguments());
	}
	
	abstract public function execute($options, $arguments);
	
}
