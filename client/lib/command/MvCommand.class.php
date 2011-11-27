<?php

require_once dirname(__FILE__).'/base/RobotCommand.class.php';

class MvCommand extends RobotCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Move a robot'
		) + parent::getParserConfig();
	}

	public function getOptionConfigs() {
		return parent::getOptionConfigs() + array(
			'relative' => array(
				'short_name' => '-l',
				'long_name' => '--relative',
				'description' => 'Use relative movement',
				'action' => 'StoreTrue'
			)
		);
	}
	
	public function getArgumentConfigs() {
		return parent::getArgumentConfigs() + array(
			'sector' => array(
				'description' => 'Destination sector coordinates (example: 45,230)'
			)
		);
	}
	
	public function execute($options, $arguments) {
		$coords = explode(',', $arguments['sector']);
		foreach (array('x', 'y') as $key=>$coord) {
			$$coord = empty($coords[$key])? 0 : $coords[$key];
		}
		$this->postForm('robot', 'robot/move/id/'.$options['robot_id'], array(
			'x' => $x,
			'y' => $y,
			'relative' => $options['relative']
		));
	}
	
}
