<?php

require_once dirname(__FILE__).'/../BaseUserInterfaceCommand.class.php';

class ExtractCommand extends BaseUserInterfaceCommand {
	public function getParserConfig() {
		return array(
			'description' => 'Extract a letter from current sector (will be added to drops)'
		) + parent::getParserConfig();
	}

	public function getArgumentConfigs() {
		return array(
			'robot_id' => array(
				'description' => 'ID of extractor (example: 1)'
			)
		);
	}
	
	public function execute($options, $arguments) {
		$this->post('robot/extract', array(
			'id' => $arguments['robot_id'],
		));
	}
	
}
