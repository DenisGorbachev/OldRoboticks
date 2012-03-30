<?php

require_once dirname(__FILE__) . '/RobotCommand.class.php';

abstract class FunCommand extends RobotCommand {
    public $cache;

    public function getOptionConfigs() {
        return array_merge(parent::getOptionConfigs(), array(
            'steps' => array(
                'short_name' => '-s',
                'long_name' => '--steps',
                'description' => 'Advance the function this number of steps',
                'action' => 'StoreInt',
                'default' => null
            )
        ),array(
            'reset' => array(
                'short_name' => '-r',
                'long_name' => '--reset',
                'description' => 'Reset the cache, start the function anew',
                'action' => 'StoreTrue',
                'default' => false
            )
        ));
    }

    public function execute($options, $arguments) {
        $this->cache = $this->getOption('reset')? array() : $this->getConfig()->getRobotFunCache($this->getOption('robot_id'));
        while (is_null($options['steps']) || $options['steps']--) {
            $result = $this->step($options, $arguments);
            if ($result === false) {
                break;
            }
            if ($options['steps'] && is_array($result) && !empty($result['active_at'])) {
                sleep(max($result['active_at'] - time(), 0));
            }
        }
        $this->getConfig()->setRobotFunCache($this->getOption('robot_id'), $this->cache);
    }

    abstract public function step($options, $arguments);

}
