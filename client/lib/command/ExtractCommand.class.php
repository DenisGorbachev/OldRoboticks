<?php

require_once dirname(__FILE__).'/base/RobotCommand.class.php';

class ExtractCommand extends RobotCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Extract a letter from current sector (will be added to drops)'
		) + parent::getParserConfig();
	}

    public function getOptionConfigs() {
        return parent::getOptionConfigs();
    }

    public function getArgumentConfigs() {
        return parent::getArgumentConfigs();
    }

	public function execute($options, $arguments) {
		return $this->post('robot/extract', array(
			'id' => $options['robot_id'],
		));
	}
	
}
