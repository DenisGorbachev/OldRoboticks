<?php

require_once dirname(__FILE__).'/base/RobotCommand.class.php';

class PickCommand extends RobotCommand {
	public function getParserConfig() {
		return array_merge(parent::getParserConfig(), array(
			'description' => 'Pick a letter from sector drops'
		));
	}

	public function getOptionConfigs() {
		return array_merge(parent::getOptionConfigs(), array());
	}
	
	public function getArgumentConfigs() {
		return array_merge(parent::getArgumentConfigs(), array(
			'letter' => array(
				'description' => 'A letter to pick (example: M)'
			)
		));
	}
	
	public function execute($options, $arguments) {
		$this->post('robot/pick', array(
			'id' => $options['robot_id'],
			'letter' => $arguments['letter'],
		));
	}
	
}
