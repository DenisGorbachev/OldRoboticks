<?php

require_once dirname(__FILE__).'/base/UserInterfaceCommand.class.php';

class InfoCommand extends UserInterfaceCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Output data about this Roboticks client installation',
			'add_version_option' => true,
			'version' => '0.1a'
		) + parent::getParserConfig();
	}
	
	public function getOptionConfigs() {
		return array(
			'user' => array(
				'short_name' => '-u',
				'long_name' => '--user',
				'description' => 'Output the user info',
				'action' => 'StoreInt'
			)
		);
	}
	
	public function execute($options, $arguments) {
		global $argv;
		if (!array_sum($options)) {
			echoln('usage: '.$argv[0].' COMMAND [ARGS]');
			echoln();
			echoln('Available commands:');
			walk_dir(dirname(__FILE__), array($this, 'outputCommandInfo'));
			echoln();
			echoln('Execute "'.$argv[0].' COMMAND --help" for more information on a specific command.');
		}
		if (!empty($options['user'])) {
			if (is_numeric($options['user'])) {
				$this->get('user/show', array('id' => $options['user']));
			} else {
				$this->get('user/profile'); 
			}
		}
		if (!empty($options['version'])) {
			echoln('Roboticks client v'.VERSION);
		}
	}
	
	public function outputCommandInfo($file) {
        if (preg_match('/base/u', $file)) {
            return;
        }
        
		require_once $file;
		$cmdName = strtolower(str_replace('Command.class.php', '', basename($file)));
		$cmdClass = $cmdName.'Command';
		$cmd = new $cmdClass();
		$parserConfig = $cmd->getParserConfig();
		echoln('   '.$cmdName.str_repeat(' ', 11-strlen($cmdName)).$parserConfig['description']);
	}
	
}
