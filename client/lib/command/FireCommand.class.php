<?php

require_once dirname(__FILE__).'/base/RobotCommand.class.php';

class FireCommand extends RobotCommand {
	public function getParserConfig() {
		return array_merge(parent::getParserConfig(), array(
			'description' => 'Fire at enemy robot'
		));
	}

	public function getOptionConfigs() {
		return array_merge(parent::getOptionConfigs(), array());
	}
	
	public function getArgumentConfigs() {
		return array_merge(parent::getArgumentConfigs(), array(
			'enemy_robot_id' => array(
				'description' => 'ID of enemy robot (example: 3)'
			),
			'letter' => array(
				'description' => 'Letter in enemy robot to fire at'
			)
		));
	}
	
	public function execute($options, $arguments) {
		$this->post('robot/fire', array(
			'id' => $options['robot_id'],
			'enemy_id' => $arguments['enemy_robot_id'],
			'letter' => $arguments['letter'],
		));
	}
	
}
