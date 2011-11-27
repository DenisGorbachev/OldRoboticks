<?php

require_once dirname(__FILE__).'/RobotCommand.class.php';

class ScanCommand extends RobotCommand {
	public function getOptionConfigs() {
		return array_merge(parent::getOptionConfigs(), array(
			'for' => array(
				'short_name' => '-f',
				'long_name' => '--for',
				'description' => 'Type of scan to perform. Possible values are: robots, letters, drops',
				'action' => 'StoreString',
				'default' => 'robots'
			)
		));
	}
	
    public function getArgumentConfigs() {
        return array_merge(array(), parent::getArgumentConfigs());
    }
	
	public function execute($options, $arguments) {
		return $this->get('robot/scan', array(
			'id' => $options['robot_id']
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
