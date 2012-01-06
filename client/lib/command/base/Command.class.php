<?php

require_once 'Console/CommandLine.php';
require_once 'Console/CommandLine/Action.php';
require_once_dir(LIBDIR.'/action');

abstract class Command {
	public $options = array();
	public $arguments = array();

    public function __construct() {
        $this->options = $this->getDefaultOptions();
    }

	public function setOptions($options) {
		$this->options = array_merge($this->getDefaultOptions(), $options);
		return $this;
	}
	
	public function getOptions() {
		return $this->options;
	}

    public function getDefaultOptions() {
        $defaultOptions = array();
        foreach ($this->getOptionConfigs() as $option=>$config) {
            $defaultOptions[$option] = empty($config['default'])? null : $config['default'];
        }
        return $defaultOptions;
    }

	public function setOption($option, $value) {
		$this->options[$option] = $value;
	}
	
	public function getOption($option, $default = null) {
		return array_key_exists($option, $this->options)? $this->options[$option] : $default;
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
	
	public function getArgument($argument, $default = null) {
        return array_key_exists($argument, $this->arguments)? $this->arguments[$argument] : $default;
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
	
	public function parse($args) {
		$parser = new Console_CommandLine($this->getParserConfig());
		foreach ($this->getOptionConfigs() as $name=>$config) {
			$parser->addOption($name, $config);
		}
		foreach ($this->getArgumentConfigs() as $name=>$config) {
			$parser->addArgument($name, $config);
		}
		$result = $parser->parse(count($args), $args);
		$this->setOptions($result->options);
		$this->setArguments($result->args);
	}
	
	public function run() {
        $options = $this->getOptions();
        $arguments = $this->getArguments();
        $this->preExecute($options, $arguments);
		$result = $this->execute($options, $arguments);
        $this->postExecute($options, $arguments);
        return $result;
	}

    public function preExecute($options, $arguments) {}

	abstract public function execute($options, $arguments);

    public function postExecute($options, $arguments) {}

}

