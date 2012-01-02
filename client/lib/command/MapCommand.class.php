<?php

require_once dirname(__FILE__).'/base/ScanCommand.class.php';

class MapCommand extends ScanCommand {
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
        if ($response['success']) {
		    $borders = $response['borders'];
            $xfill = array_fill_negative($borders['blX'], $borders['trX']-$borders['blX']+1, ' ');
            $info = array_fill_negative($borders['blY'], $borders['trY']-$borders['blY']+1, $xfill);
            $info = array_reverse($info, true);
            $info = $this->{'executeFor'.$options['for']}($response, $info);
            foreach ($info as &$row) {
                array_unshift($row, '');
            }
            $upperCoordinatesRow = array_merge(array($this->sector($borders['blX'], $borders['trY'])), array_fill_negative(0, $borders['trX']-$borders['blX']+1, ''), array($this->sector($borders['trX'], $borders['trY'])));
            $lowerCoordinatesRow = array_merge(array($this->sector($borders['blX'], $borders['blY'])), array_fill_negative(0, $borders['trX']-$borders['blX']+1, ''), array($this->sector($borders['trX'], $borders['blY'])));
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
                $info[$y][$x] = $this->empty_cell_placeholder;
                continue;
            }
            foreach ($sector['Robots'] as $robot) {
				if ($info[$y][$x] == ' ') {
                    $info[$y][$x] = 0;
                }
                $stance = $this->getStance($robot);
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

    public function executeForDrops($response, $info)
    {
        foreach ($response['results'] as $sector) {
            $x = $sector['x'];
            $y = $sector['y'];
            $info[$y][$x] = empty($sector['drops'])? $this->empty_cell_placeholder : min(ceil(mb_strlen($sector['drops']) / 10), 9);
        }
        return $info;
	}

}
