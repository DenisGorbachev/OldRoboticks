<?php

require_once dirname(__FILE__).'/base/RobotCommand.class.php';

class MvCommand extends RobotCommand {
	public function getParserConfig() {
		return array_merge(parent::getParserConfig(), array(
			'description' => 'Move a robot'
		));
	}

	public function getOptionConfigs() {
		return array_merge(parent::getOptionConfigs(), array(
			'relative' => array(
				'short_name' => '-l',
				'long_name' => '--relative',
				'description' => 'Use relative movement',
				'action' => 'StoreTrue'
			)
		));
	}
	
	public function getArgumentConfigs() {
		return array_merge(parent::getArgumentConfigs(), array(
			'sector' => array(
				'description' => 'Destination sector coordinates (example: 45,230)'
			)
		));
	}
	
	public function execute($options, $arguments) {
		$coords = explode(',', $arguments['sector']);
        $x = $coords[0];
        $y = $coords[1];
		return $this->post('robot/move', array(
            'id' => $options['robot_id'],
			'x' => $x,
			'y' => $y,
			'relative' => $options['relative']
		));
	}
	
}
