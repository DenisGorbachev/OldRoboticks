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
            'sector' => array(
                'description' => 'Target sector coordinates (example: 45,230)'
            ),
            'letter' => array(
                'description' => 'Letter in target sector to fire at'
            )
        ));
    }

    public function execute($options, $arguments) {
        $sector = $this->getSectorArgument('sector');
        return $this->post('robot/fire', array(
            'id' => $options['robot_id'],
            'x' => $sector['x'],
            'y' => $sector['y'],
            'letter' => $arguments['letter'],
        ));
    }

}
