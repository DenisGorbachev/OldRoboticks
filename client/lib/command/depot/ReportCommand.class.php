<?php

require_once dirname(__FILE__).'/../BaseScanCommand.class.php';

class ReportCommand extends BaseScanCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Show a report of robots surroundings'
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
            $this->table($this->{'executeFor'.$options['for']}($response));
        }
	}
	
    public function executeForRobots($response)
    {
        $info = array('ID', 'Name', 'Status', 'User', );
        foreach ($response['results'] as $sector) {
            $x = $sector['x'];
            $y = $sector['y'];
            if (empty($sector['Robots'])) {
                continue;
            }
            foreach ($sector['Robots'] as $robot) {
                $stance = $this->getStance($robot);
                $this->stance_values[$stance];
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
