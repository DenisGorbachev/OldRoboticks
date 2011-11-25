<?php

require_once dirname(__FILE__).'/../BaseScanCommand.class.php';

class MapCommand extends BaseScanCommand {
	public $stance_values = array(
		'enemy' => 1,
        'ally' => 2,
        'own' => 4
	);
	
	public function getParserConfig() {
		return array(
			'description' => 'Show a map of robot surroundings'
		) + parent::getParserConfig();
	}

	public function getOptionConfigs() {
		return parent::getOptionConfigs();
	}
	
	public function getArgumentConfigs() {
		return parent::getArgumentConfigs();
	}
	
	public function execute($options, $arguments) {
		$response = parent::execute($options, $arguments);
        if ($response) {
		    $borders = $response['borders'];
            $xfill = array_fill($borders['blX'], $borders['trX']-$borders['blX']+1, $this->empty_cell_placeholder);
            $info = array_fill($borders['blY'], $borders['trY']-$borders['blY']+1, $xfill);
            $info = array_reverse($info, true);
            $info = $this->{'executeFor'.$options['for']}($response, $info);
            foreach ($info as &$row) {
                array_unshift($row, '');
            }
            $upperCoordinatesRow = array_merge(array($this->coords($borders['blX'], $borders['trY'])), array_fill(0, $borders['trX']-$borders['blX']+1, ''), array($this->coords($borders['trX'], $borders['trY'])));
            $lowerCoordinatesRow = array_merge(array($this->coords($borders['blX'], $borders['blY'])), array_fill(0, $borders['trX']-$borders['blX']+1, ''), array($this->coords($borders['trX'], $borders['blY'])));
            array_unshift($info, $upperCoordinatesRow);
            $info[] = $lowerCoordinatesRow;
            $this->table($info);
        }
	}
	
    public function executeForRobots($response, $info)
    {
        foreach ($response['results'] as $sector) {
            $x = $sector['x'];
            $y = $sector['y'];
            if (empty($sector['Robots'])) {
                continue;
            }
            foreach ($sector['Robots'] as $robot) {
				if ($info[$y][$x] == $this->empty_cell_placeholder) {
                    $info[$y][$x] = 0;
                }
                $stance = 'enemy';
                $stancesFrom = $robot['User']['StancesFrom'];
                if ($stancesFrom) {
					if ($stancesFrom[0]['type']) {
						$stance = $stancesFrom[0]['type'];
					}
				} else if ($robot['user_id'] == $this->getUserId()) {
                    $stance = 'own';
                }
                $info[$y][$x] = $info[$y][$x] | $this->stance_values[$stance];
            }
        }
        return $info;
    }

    public function executeForLetters($response, $info)
    {
        foreach ($response['results'] as $sector) {
            $x = $sector['x'];
            $y = $sector['y'];
            $info[$y][$x] = empty($sector['letter'])? $this->empty_cell_placeholder : $sector['letter'];
        }
        return $info;
	}

}
