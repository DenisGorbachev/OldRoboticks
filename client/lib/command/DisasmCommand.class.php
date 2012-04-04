<?php

require_once dirname(__FILE__).'/base/RobotCommand.class.php';

class DisasmCommand extends RobotCommand {
    public function getParserConfig() {
        return array_merge(parent::getParserConfig(), array(
            'description' => 'Disassemble a robot'
        ));
    }

    public function getOptionConfigs() {
        return array_merge(parent::getOptionConfigs(), array());
    }

    public function getArgumentConfigs() {
        return array_merge(parent::getArgumentConfigs(), array(
            'target_robot_id' => array(
                'description' => 'ID of robot to disassemble (example: 17)'
            )
        ));
    }

    public function execute($options, $arguments) {
        return $this->post('robot/disassemble', array(
            'id' => $options['robot_id'],
            'target_id' => $arguments['target_robot_id'],
        ));
    }

}
