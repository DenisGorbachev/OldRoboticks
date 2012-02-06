<?php

require_once dirname(__FILE__) . '/../base/FunCommand.class.php';
require_once dirname(__FILE__) . '/../MvCommand.class.php';

class FunMvCommand extends FunCommand {
	public function getParserConfig() {
		return array_merge(parent::getParserConfig(), array(
			'description' => 'Move until destination sector is reached'
		));
	}

	public function getArgumentConfigs() {
		return array_merge(parent::getArgumentConfigs(), array(
            'sector' => array(
                'description' => 'Destination sector coordinates (example: 45,230)'
            )
		));
	}

    public function step($options, $arguments) {
        $command = new MvCommand($this->getConfig());
        $command->setOptions($this->getOptions());
        $command->setArguments($this->getArguments());
        $result = $command->run();
        if (isset($result['message']['arguments']['sector']) && $result['message']['arguments']['sector'] == $this->getArgument('sector')) {
            return false;
        }
        return $result;
    }

}
