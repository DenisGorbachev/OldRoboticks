<?php

require_once dirname(__FILE__).'/base/RobotCommand.class.php';

class DropCommand extends RobotCommand {
    public function getParserConfig() {
        return array_merge(parent::getParserConfig(), array(
            'description' => 'Drop a letter carried by robot'
        ));
    }

    public function getOptionConfigs() {
        return array_merge(parent::getOptionConfigs(), array());
    }

    public function getArgumentConfigs() {
        return array_merge(parent::getArgumentConfigs(), array(
            'letter' => array(
                'description' => 'A letter to drop (example: M)'
            )
        ));
    }

    public function execute($options, $arguments) {
        return $this->post('robot/drop', array(
            'id' => $options['robot_id'],
            'letter' => $arguments['letter'],
        ));
    }

}
