<?php

require_once dirname(__FILE__).'/../BaseUserInterfaceCommand.class.php';

class MvCommand extends BaseUserInterfaceCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Move a robot'
		) + parent::getParserConfig();
	}

	public function getOptionConfigs() {
		return array(
			'relative' => array(
				'short_name' => '-r',
				'long_name' => '--relative',
				'description' => 'Use relative movement',
				'action' => 'StoreTrue'
			)
		);
	}
	
	public function getArgumentConfigs() {
		return array(
			'robot_id' => array(
				'description' => 'ID of robot to move (example: 1)'
			),
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
		$this->postForm('robot', 'robot/move/id/'.$arguments['robot_id'], array(
			'x' => $x,
			'y' => $y,
			'relative' => $options['relative']
		));
	}
	
}
