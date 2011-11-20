<?php

require_once dirname(__FILE__).'/../BaseServerCommand.class.php';

class ScanCommand extends BaseServerCommand {
	public $colors = array(
		'own' => 'red'
	);
	
	public function getParserConfig() {
		return array(
			'description' => 'Scan sectors surrounding a robot'
		) + parent::getParserConfig();
	}

	public function getOptionConfigs() {
		return array(
			'for' => array(
				'short_name' => '-f',
				'long_name' => '--for',
				'description' => 'Type of scan to perform',
				'action' => 'StoreArray',
				'action_params' => array('choices' => array('r' => 'robots', 'l' => 'letters', 'd' => 'drops')),
				'default' => 'robots'
			)
		);
	}
	
	public function getArgumentConfigs() {
		return array(
			'robot_id' => array(
				'description' => 'ID of scan executor (example: 1)'
			)
		);
	}
	
	public function execute($options, $arguments) {
		$response = $this->get('robot/scan', array(
			'id' => $arguments['robot_id'],
			'for' => $options['for']
		));
		return $this->{'executeFor'.$options['for']}($response['results']);
	}
	
	public function executeForRobots(array $results) {
		$info = array();
		foreach ($results as $result) {
			vd($result);
			extract($result);
			if (empty($info[$y])) {
				$info[$y] = array();
			}
			if (empty($info[$y][$x])) {
				$info[$y][$x] = array(
					'own' => 0,
					'ally' => 0,
					'enemy' => 0
				);
			}
			if (empty($robot_id)) {
				continue;
			}
			$stance = $stance ?: 'own';
			$info[$y][$x][$stance]++;
		}
		foreach ($info as $row) {
			foreach ($row as $cell) {
				echo ' ';
			}
			echo PHP_EOL;
		}
	}
	
}
