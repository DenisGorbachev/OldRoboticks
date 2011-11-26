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
			'id' => $arguments['robot_id']
		));
	}

    public function getStance($robot)
    {
        $stance = 'enemy';
        $stancesFrom = $robot['User']['StancesFrom'];
        if ($stancesFrom) {
            if ($stancesFrom[0]['type']) {
                $stance = $stancesFrom[0]['type'];
            }
        } else if ($robot['user_id'] == $this->getUserId()) {
            $stance = 'own';
        }
        return $stance;
    }

}
