<?php

require_once dirname(__FILE__).'/../BaseUserInterfaceCommand.class.php';

class ScanCommand extends BaseUserInterfaceCommand {
	public $stance_values = array(
		'enemy' => 1,
        'ally' => 2,
        'own' => 4
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
        $minX = null;
        $minY = null;
        $maxX = null;
        $maxY = null;
		foreach ($results as $i => $result) {
			$x = $result['x'];
            $y = $result['y'];
            $stance = $result['stance'] ?: 'own';
            $robot_id = $result['robot_id'];
            if ($x > $maxX || is_null($maxX)) {
                $maxX = $x;
            }
            if ($x < $minX || is_null($minX)) {
                $minX = $x;
            }
            if ($y > $maxY || is_null($maxY)) {
                $maxY = $y;
            }
            if ($y < $minY || is_null($minY)) {
                $minY = $y;
            }
			if (empty($info[$y])) {
				$info[$y] = array();
			}
			if (empty($info[$y][$x])) {
				$info[$y][$x] = $this->empty_cell_placeholder;
			}
			if (empty($robot_id)) {
				continue;
			}
            if ($info[$y][$x] == $this->empty_cell_placeholder) {
				$info[$y][$x] = 0;
			}
			$info[$y][$x] = $info[$y][$x] | $this->stance_values[$stance];
		}
        foreach ($info as &$row) {
            array_unshift($row, '');
        }
        $upperCoordinatesRow = array_merge(array($this->coords($minX, $maxY)), array_fill(0, $maxX-$minX+1, ''), array($this->coords($maxX, $maxY)));
        $lowerCoordinatesRow = array_merge(array($this->coords($minX, $minY)), array_fill(0, $maxX-$minX+1, ''), array($this->coords($maxX, $minY)));
        array_unshift($info, $upperCoordinatesRow);
        $info[] = $lowerCoordinatesRow;
		$this->table($info);
	}
	
}
