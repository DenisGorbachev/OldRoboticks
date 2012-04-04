<?php

require_once dirname(__FILE__).'/base/ScanCommand.class.php';

class ReportCommand extends ScanCommand {
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
        if ($response['success']) {
            $this->table($this->{'executeFor'.$options['for']}($response));
        }
        return $response;
    }

    public function executeForRobots($response)
    {
        $info = array(array('ID', 'Stance', 'Status', 'Sector', 'Owner', 'Name', 'Speed',));
        foreach ($response['results'] as $sector) {
            if (empty($sector['Robots'])) {
                continue;
            }
            foreach ($sector['Robots'] as $robot) {
                $info[] = array(
                    $robot['id'],
                    $this->getStance($robot),
                    $robot['status'],
                    $this->coords($sector),
                    $robot['User']['username'],
                    $robot['Word']['name'],
                    $robot['speed'],
                );
            }
        }
        return $info;
    }

    public function executeForLetters($response)
    {
        $info = array(array('Sector', 'Letter'));
        foreach ($response['results'] as $sector) {
            if (empty($sector['letter'])) {
                continue;
            }
            $info[] = array($this->coords($sector), $sector['letter']);
        }
        return $info;
    }

    public function executeForDrops($response)
    {
        $info = array(array('Sector', 'Drops'));
        foreach ($response['results'] as $sector) {
            if (empty($sector['drops'])) {
                continue;
            }
            $info[] = array($this->coords($sector), implode(' ', str_split($sector['drops'])));
        }
        return $info;
    }

}
