<?php

require_once dirname(__FILE__).'/base/RobotCommand.class.php';

class FireCommand extends RobotCommand {
    public function getParserConfig() {
        return array_merge(parent::getParserConfig(), array(
            'description' => 'Fire at target robot'
        ));
    }

    public function getOptionConfigs() {
        return array_merge(parent::getOptionConfigs(), array());
    }

    public function getArgumentConfigs() {
        return array_merge(parent::getArgumentConfigs(), array(
            'target_robot_id' => array(
                'description' => 'ID of robot to fire at (example: 3)'
            ),
            'letter' => array(
                'description' => 'Letter in target robot to fire at'
            )
        ));
    }

    public function execute($options, $arguments) {
        return $this->post('robot/fire', array(
            'id' => $options['robot_id'],
            'target_id' => $arguments['target_robot_id'],
            'letter' => $arguments['letter'],
        ));
    }

}
