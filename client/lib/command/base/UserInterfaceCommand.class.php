<?php

require_once dirname(__FILE__).'/ServerCommand.class.php';

abstract class UserInterfaceCommand extends ServerCommand {
    public $output_format;

    public $empty_cell_placeholder = '-';

    public $notifications = array();

    public $responses = array();

    public function run()
    {
        $this->initOutputFormat();
        $this->initLogging();
        $this->log(PHP_EOL.implode(' ', $_SERVER['argv']));
        
        return parent::run();
    }

    public function setVariable($name, $value) {
        file_put_contents($this->getVariableFilename($name), $value);
    }

    public function getVariable($name, $default = null) {
        $value = getenv('RK_'.strtoupper($name));
        if ($value) {
            return $value;
        }
        $filename = $this->getVariableFilename($name);
        if (file_exists($filename)) {
            $value = file_get_contents($filename);
            if ($value) {
                return $value;
            }
        }
        return $default;
    }

    public function getVariableFilename($name) {
        return CACHEDIR . '/' . $name;
    }

    public function initLogging() {
        foreach ($this->getLogFilenames() as $filename) {
            $dirname = dirname($filename);
            if (!file_exists($dirname)) {
                mkdir($dirname, 0755, true);
            }
        }
    }

    public function getLogFilenames() {
        return array(
            $this->getBaseLogDirname().'/all'
        );
    }

    public function getBaseLogDirname() {
        return LOGDIR.'/'.Config::get('generic/server/host', 'undefined');
    }

    public function request($controller, $parameters = array(), $method = 'GET', $options = array()) {
        do {
            $response = parent::request($controller, $parameters, $method, $options);
            switch ($this->output_format) {
                case 'none':
                    break;
                case 'json':
                    $this->responses[] = $response;
                    break;
                case 'human':
                    $message = __($response['message']);
                    $this->{$response['message']['type']}($message);
                    if ($response['success']) {

                        if (!empty($response['notifications'])) {
                            $this->notifications = array_merge($this->notifications, $response['notifications']);
                        }
                    } else {
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
            $haveToWait = $response['message']['type'] == 'wait';
        } while ($haveToWait && (sleep($response['message']['arguments']['amount']) === 0));
        return $response;
    }

    public function table($table, $sortByColumnIndex = false)
    {
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

        return $this->tableFixedColumnWidth($table, $sortByColumnIndex, $maxlengths);
    }

    public function tableFixedColumnWidth($table, $sortByColumnIndex, $maxlengths)
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
            $rowString = '';
            foreach ($row as $columnIndex => $cell) {
                $rowString .= str_pad((string)$cell, min(40, $maxlengths[$columnIndex]) + 2); // spaces between columns
            }
            $this->echoln($rowString);
        }
    }

    public function sector($x, $y) {
        return $x.','.$y;
    }

    public function coords($sector) {
        return $this->sector($sector['x'], $sector['y']);
    }

    public function log($string = '') {
        foreach ($this->getLogFilenames() as $filename) {
            file_put_contents($filename, $string.PHP_EOL, FILE_APPEND);
        }
    }

    public function echoln($string = '') {
        if ($this->output_format == 'human') {
            echo $string.PHP_EOL;
        }
        $this->log($string);
    }

    public function success($message) {
        return $this->echoln('Success: '.$message);
    }

    public function notice($message) {
        return $this->echoln('Notice: '.$message);
    }

    public function failure($message) {
        return $this->echoln('Failure: '.$message);
    }

    public function wait($message) {
        return $this->echoln('Please wait: '.$message);
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

    public function setOutputFormat($output_format) {
        $this->output_format = $output_format;
    }

    public function getOutputFormat() {
        return $this->output_format;
    }

    public function initOutputFormat() {
        $this->output_format = getenv('RK_OUTPUT_FORMAT') ?: 'human';
    }

}
