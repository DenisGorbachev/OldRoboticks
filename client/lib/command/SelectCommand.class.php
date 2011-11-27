<?php

require_once dirname(__FILE__).'/base/Command.class.php';

class SelectCommand extends Command {
	public function getParserConfig() {
		return array(
			'description' => 'Select a robot ID'
		) + parent::getParserConfig();
	}

	public function getArgumentConfigs() {
		return array(
			'robot_id' => array(
				'description' => 'ID of selected robot (example: 10)'
			)
		);
	}
	
	public function execute($options, $arguments) {
        $robotId = (int)$arguments['robot_id'];
        if ((string)$robotId != $arguments['robot_id']) {
            throw new RoboticksArgumentException('Invalid argument "robot_id": expected integer, but got "'.$arguments['robot_id'].'"');
        }
        file_put_contents(CACHEDIR.'/robotId', $robotId);
        echoln('Success: selected robot #'.$robotId);
	}
	
}
