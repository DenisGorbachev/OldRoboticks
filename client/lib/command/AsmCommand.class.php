<?php

require_once dirname(__FILE__).'/base/RobotCommand.class.php';

class AsmCommand extends RobotCommand {
	public function getParserConfig() {
		return array_merge(parent::getParserConfig(), array(
			'description' => 'Assemble a new robot'
		));
	}

	public function getOptionConfigs() {
		return array_merge(parent::getOptionConfigs(), array());
	}
	
	public function getArgumentConfigs() {
		return array_merge(parent::getArgumentConfigs(), array(
			'name' => array(
				'description' => 'Name of a new robot (example: BABY)'
			)
		));
	}
	
	public function execute($options, $arguments) {
		$this->post('robot/assemble', array(
			'id' => $options['robot_id'],
			'name' => $arguments['name'],
		));
	}
	
}
