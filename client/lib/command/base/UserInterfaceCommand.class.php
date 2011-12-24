<?php

require_once dirname(__FILE__).'/ServerCommand.class.php';

abstract class UserInterfaceCommand extends ServerCommand {
    public $output_format = 'human';

    public $empty_cell_placeholder = '-';

    public $notifications = array();

    public $responses = array();

    public function run()
    {
        $this->output_format = getenv('RK_OUTPUT_FORMAT') ?: $this->output_format;
        
        return parent::run();
    }

    public function selectRobotId($robotId) {
        file_put_contents(CACHEDIR . '/robotId', $robotId);
    }

    public function selectRealmId($realmId) {
        file_put_contents(CACHEDIR . '/realmId', $realmId);
    }

    public function request($controller, $parameters = array(), $method = 'GET', $options = array()) {
        $response = parent::request($controller, $parameters, $method, $options);
        switch ($this->output_format) {
            case 'none':
                break;
            case 'json':
                $this->responses[] = $response;
                break;
            case 'human':
                $message = __($response['message']);
                if ($response['success']) {
                    $this->echoln('Success: '.$message);
                    if (!empty($response['notifications'])) {
                        $this->notifications = array_merge($this->notifications, $response['notifications']);
                    }
                } else {
                    $this->echoln('Failure: '.$message);
                    if (!empty($response['globalErrors'])) {
                        foreach ($response['globalErrors'] as $error) {
                            $this->echoln('  - '.__($error));
                        }
                    }
                    if (!empty($response['errors'])) {
                        foreach ($response['errors'] as $field=>$error) {
                            $this->echoln('  - '.__(array('text' => ucfirst($field), 'arguments' => array())).': '.__($error));
                        }
                    }
                }
                break;
            default:
                throw new RoboticksUnknownOutputFormatException('Unknown output format');
                break;
        }
        return $response;
    }

    public function table($table, $sortByColumnIndex = false)
    {
        if ($sortByColumnIndex) {
            usort($table, function($a, $b) use ($sortByColumnIndex)
                {
                    if ($a[$sortByColumnIndex] == $b[$sortByColumnIndex]) {
                        return 0;
                    }
                    return ($a[$sortByColumnIndex] < $b[$sortByColumnIndex]) ? -1 : 1;
                }
            );
        }

        foreach ($table as $row) {
            foreach ($row as $columnIndex => $cell) {
                if (empty($maxlengths[$columnIndex])) {
                    $maxlengths[$columnIndex] = 0;
                }
                if (mb_strlen((string)$cell) > $maxlengths[$columnIndex]) {
                    $maxlengths[$columnIndex] = mb_strlen($cell);
                }
            }
        }

        foreach ($table as $row) {
            $rowString = '';
            foreach ($row as $columnIndex => $cell) {
                $rowString .= str_pad((string)$cell, min(40, $maxlengths[$columnIndex]) + 2); // spaces between columns
            }
            $this->echoln($rowString.PHP_EOL);
        }
    }

    public function sector($x, $y) {
        return $x.','.$y;
    }

    public function coords($sector) {
        return $this->sector($sector['x'], $sector['y']);
    }

    public function echoln($string = '') {
        if ($this->output_format == 'human') {
            echo $string.PHP_EOL;
        }
    }

    public function postExecute($options, $arguments) {
        switch ($this->output_format) {
            case 'none':
                break;
            case 'json':
                echo json_encode($this->responses);
                break;
            case 'human':
                foreach ($this->notifications as $notification) {
                    $this->echoln(__($notification));
                }
                break;
            default:
                throw new RoboticksUnknownOutputFormatException('Unknown output format');
                break;
        }
        parent::postExecute($options, $arguments);
    }

}
