<?php

require_once dirname(__FILE__).'/base/RobotCommand.class.php';

class RepairCommand extends RobotCommand {
	public function getParserConfig() {
		return array_merge(parent::getParserConfig(), array(
			'description' => 'Repair one letter in target robot'
		));
	}

	public function getOptionConfigs() {
		return array_merge(parent::getOptionConfigs(), array());
	}
	
	public function getArgumentConfigs() {
		return array_merge(parent::getArgumentConfigs(), array(
			'target_robot_id' => array(
				'description' => 'ID of robot to repair (example: 3)'
			),
			'letter' => array(
				'description' => 'Letter in target robot to repair'
			)
		));
	}
	
	public function execute($options, $arguments) {
		$this->post('robot/repair', array(
			'id' => $options['robot_id'],
			'target_id' => $arguments['target_robot_id'],
			'letter' => $arguments['letter'],
		));
	}
	
}
