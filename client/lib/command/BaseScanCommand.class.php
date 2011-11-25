<?php

require_once dirname(__FILE__).'/BaseUserInterfaceCommand.class.php';

class BaseScanCommand extends BaseUserInterfaceCommand {
	public function getOptionConfigs() {
		return array(
			'for' => array(
				'short_name' => '-f',
				'long_name' => '--for',
				'description' => 'Type of scan to perform. Possible values are: robots, letters, drops',
				'action' => 'StoreString',
				'default' => 'robots'
			)
		);
	}
	
	public function getArgumentConfigs() {
		return array(
			'robot_id' => array(
				'description' => 'ID of robot executing the scan (example: 1)'
			)
		);
	}
	
	public function execute($options, $arguments) {
		return $this->get('robot/scan', array(
			'id' => $arguments['robot_id'],
			'for' => $options['for']
		));
	}
}
